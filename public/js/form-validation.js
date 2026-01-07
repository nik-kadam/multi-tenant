$(document).ready(function(){

    // Registration logic
    $('#signupForm').validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            }
        },
        messages: {
            name: {
                required: "Please enter your name"
            },
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please enter your password",
                minlength: "Your password must be at least 8 characters long"
            }
        },
        submitHandler: function(form){

            $('#signupBtn').prop('disabled', true);
            $('#signupBtn').text('Processing...');            
            
            $.ajax({
                url: baseUrl+'/tenant-signup',
                type: "POST",
                data: $(form).serialize(),
                success: function(response){
                    if(response.success == true){
                        window.location.href = baseUrl+'/dashboard';
                    }else{
                        $('#signupBtn').prop('disabled', false);
                        $('#signupBtn').text('Register');
                        toastr.error(response.message);   
                    }
                },
                error: function(xhr, status, error){
                    $('#signupBtn').prop('disabled', false);
                    $('#signupBtn').text('Register');
                    toastr.error(xhr.responseJSON.message);
                }
            });

        }
    });
    
    // Login form validation
    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            }
        },
        messages: {
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please enter your password",
                minlength: "Your password must be at least 8 characters long"
            }
        },
        submitHandler: function(form){

            $('#loginBtn').prop('disabled', true);
            $('#loginBtn').text('Processing...');           
            
            $.ajax({
                url: baseUrl+'/tenant-login',
                type: "POST",
                data: $(form).serialize(),
                success: function(response){
                    if(response.success == true){
                        window.location.href = baseUrl+'/dashboard';
                    }else{
                        $('#loginBtn').prop('disabled', false);
                        $('#loginBtn').text('Login');
                        toastr.error(response.message);   
                    }
                },
                error: function(xhr, status, error){
                    $('#loginBtn').prop('disabled', false);
                    $('#loginBtn').text('Login');
                    toastr.error(xhr.responseJSON.message);
                }
            });

        }
    });

    // Employee form validation
    $('#employeeForm').validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: baseUrl+'/check-email',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        email: function() {
                            return $("#email").val();
                        }
                    }
                }
            },
            position: {
                required: true
            },
            department: {
                required: true
            },
            salary: {
                required: true
            },
            joining_date: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter your name"
            },
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                remote: "Email already exists"
            },
            position: {
                required: "Please enter your position"
            },
            department: {
                required: "Please enter your department"
            },
            salary: {
                required: "Please enter your salary"
            },
            joining_date: {
                required: "Please enter your joining date"
            }
        },
        submitHandler: function(form){
            
            $('#addEmployeeSaveBtn').prop('disabled', true);
            $('#addEmployeeSaveBtn').text('Saving...'); 

            $.ajax({
                url: baseUrl+'/add-employee',
                type: "POST",
                data: $(form).serialize(),
                success: function(response){
                    if(response.success == true){
                        toastr.success(response.message);
                        $('#addEmployeeSaveBtn').prop('disabled', false);
                        $('#addEmployeeSaveBtn').text('Save');
                        $('.modal').hide();
                        setTimeout(function(){
                            window.location.href = baseUrl+'/dashboard';
                        }, 1500);
                    }else{
                        toastr.error(response.message);   
                        $('#addEmployeeSaveBtn').prop('disabled', false);
                        $('#addEmployeeSaveBtn').text('Save');
                    }
                },
                error: function(xhr, status, error){
                    toastr.error(xhr.responseJSON.message);
                    $('#addEmployeeSaveBtn').prop('disabled', false);
                    $('#addEmployeeSaveBtn').text('Save');
                }
            });

        }
    });

    // Edit Employee form validation
    $('#editForm').validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true,
            },
            position: {
                required: true
            },
            department: {
                required: true
            },
            salary: {
                required: true
            },
            joining_date: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter your name"
            },
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
            },
            position: {
                required: "Please enter your position"
            },
            department: {
                required: "Please enter your department"
            },
            salary: {
                required: "Please enter your salary"
            },
            joining_date: {
                required: "Please enter your joining date"
            }
        },
        submitHandler: function(form){

            $('#editEmployeeBtn').prop('disabled', true);
            $('#editEmployeeBtn').text('Saving...');
            
            $.ajax({
                url: baseUrl+'/update-employee',
                type: "POST",
                data: $(form).serialize(),
                success: function(response){
                    if(response.success == true){
                        toastr.success(response.message);
                        $('#editEmployeeBtn').prop('disabled', false);
                        $('#editEmployeeBtn').text('Save');
                        $('.modal').hide();
                        setTimeout(function(){
                            window.location.href = baseUrl+'/dashboard';
                        }, 1500);
                    }else{
                        toastr.error(response.message);   
                        $('#editEmployeeBtn').prop('disabled', false);
                        $('#editEmployeeBtn').text('Save');
                    }
                },
                error: function(xhr, status, error){
                    toastr.error(xhr.responseJSON.message);
                    $('#editEmployeeBtn').prop('disabled', false);
                    $('#editEmployeeBtn').text('Save');
                }
            });

        }
    });

    // Delete Employee form validation
    $('#deleteForm').validate({
        rules: {
        },
        messages: {
        },
        submitHandler: function(form){

            $('#confirmDelete').prop('disabled', true);
            $('#confirmDelete').text('Deleting...');
            
            $.ajax({
                url: baseUrl+'/delete-employee',
                type: "POST",
                data: $(form).serialize(),
                success: function(response){
                    if(response.success == true){
                        toastr.success(response.message);
                        $('#confirmDelete').prop('disabled', false);
                        $('#confirmDelete').text('Delete');
                        $('.modal').hide();
                        setTimeout(function(){
                            window.location.href = baseUrl+'/dashboard';
                        }, 1500);
                    }else{
                        toastr.error(response.message);   
                        $('#confirmDelete').prop('disabled', false);
                        $('#confirmDelete').text('Delete');
                    }
                },
                error: function(xhr, status, error){
                    toastr.error(xhr.responseJSON.message);
                    $('#confirmDelete').prop('disabled', false);
                    $('#confirmDelete').text('Delete');
                }
            });

        }
    });
});

// Department Module
$(document).ready(function() {
    var modal = $('#departmentModal');
    var editModal = $('#editDepartmentModal');
    var deleteModal = $('#deleteDepartmentModal');

    // Open Add Modal
    $('#addDepartmentBtn').on('click', function() {
        modal.show();
    });

    // Close Modals
    $('.close, .close-delete, .close-modal').on('click', function() {
        modal.hide();
        editModal.hide();
        deleteModal.hide();
    });

    $(window).on('click', function(event) {
        if ($(event.target).is(modal) || $(event.target).is(editModal) || $(event.target).is(deleteModal)) {
            modal.hide();
            editModal.hide();
            deleteModal.hide();
        }
    });

    // Add Department
    $('#departmentForm').validate({
        rules: {
            name: "required"
        },
        submitHandler: function(form) {
            $('#saveDepartmentBtn').prop('disabled', true).text('Saving...');
            $.ajax({
                url: baseUrl + '/add-department',
                type: "POST",
                data: $(form).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() { location.reload(); }, 1000);
                    } else {
                        toastr.error(response.message);
                        $('#saveDepartmentBtn').prop('disabled', false).text('Save');
                    }
                },
                error: function(xhr) {
                    $('#saveDepartmentBtn').prop('disabled', false).text('Save');
                    toastr.error('Error occurred');
                }
            });
        }
    });

    // Edit Department Click
    $(document).on('click', '.editDepartment', function() {
        var id = $(this).data('id');
        $.ajax({
            url: baseUrl + '/get-department-details/' + id,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    $('#departmentId').val(response.data.id);
                    $('#edit_name').val(response.data.name);
                    editModal.show();
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Update Department
    $('#editDepartmentForm').validate({
        rules: {
            edit_name: "required"
        },
        submitHandler: function(form) {
            $('#updateDepartmentBtn').prop('disabled', true).text('Updating...');
            $.ajax({
                url: baseUrl + '/update-department',
                type: "POST",
                data: $(form).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() { location.reload(); }, 1000);
                    } else {
                        toastr.error(response.message);
                        $('#updateDepartmentBtn').prop('disabled', false).text('Update');
                    }
                },
                error: function(xhr) {
                    $('#updateDepartmentBtn').prop('disabled', false).text('Update');
                    toastr.error('Error occurred');
                }
            });
        }
    });

    // Delete Department Click
    $(document).on('click', '.deleteDepartment', function() {
        var id = $(this).data('id');
        $('#delDepartmentId').val(id);
        deleteModal.show();
    });

    // Confirm Delete
    $('#deleteDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        $('#confirmDelete').prop('disabled', true).text('Deleting...');
        $.ajax({
            url: baseUrl + '/delete-department',
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    toastr.error(response.message);
                    $('#confirmDelete').prop('disabled', false).text('Delete');
                }
            },
            error: function(xhr) {
                $('#confirmDelete').prop('disabled', false).text('Delete');
                toastr.error('Error occurred');
            }
        });
    });
});

// Roles Module
$(document).ready(function() {
    var modal = $('#roleModal');
    var editModal = $('#editRoleModal');
    var deleteModal = $('#deleteRoleModal');

    // Open Add Modal
    $('#addRoleBtn').on('click', function() {
        modal.show();
    });

    // Close Modals
    $('.close, .close-delete, .close-modal').on('click', function() {
        modal.hide();
        editModal.hide();
        deleteModal.hide();
    });

    $(window).on('click', function(event) {
        if ($(event.target).is(modal) || $(event.target).is(editModal) || $(event.target).is(deleteModal)) {
            modal.hide();
            editModal.hide();
            deleteModal.hide();
        }
    });

    // Add Role
    $('#roleForm').validate({
        rules: {
            name: "required"
        },
        submitHandler: function(form) {
            $('#saveRoleBtn').prop('disabled', true).text('Saving...');
            $.ajax({
                url: baseUrl + '/add-role',
                type: "POST",
                data: $(form).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() { location.reload(); }, 1000);
                    } else {
                        toastr.error(response.message);
                        $('#saveRoleBtn').prop('disabled', false).text('Save');
                    }
                },
                error: function(xhr) {
                    $('#saveRoleBtn').prop('disabled', false).text('Save');
                    toastr.error('Error occurred');
                }
            });
        }
    });

    // Edit Role Click
    $(document).on('click', '.editRole', function() {
        var id = $(this).data('id');
        $.ajax({
            url: baseUrl + '/get-role-details/' + id,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    $('#roleId').val(response.data.id);
                    $('#edit_name').val(response.data.name);
                    editModal.show();
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Update Role
    $('#editRoleForm').validate({
        rules: {
            edit_name: "required"
        },
        submitHandler: function(form) {
            $('#updateRoleBtn').prop('disabled', true).text('Updating...');
            $.ajax({
                url: baseUrl + '/update-role',
                type: "POST",
                data: $(form).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() { location.reload(); }, 1000);
                    } else {
                        toastr.error(response.message);
                        $('#updateRoleBtn').prop('disabled', false).text('Update');
                    }
                },
                error: function(xhr) {
                    $('#updateRoleBtn').prop('disabled', false).text('Update');
                    toastr.error('Error occurred');
                }
            });
        }
    });

    // Delete Role Click
    $(document).on('click', '.deleteRole', function() {
        var id = $(this).data('id');
        $('#delRoleId').val(id);
        deleteModal.show();
    });

    // Confirm Delete
    $('#deleteRoleForm').on('submit', function(e) {
        e.preventDefault();
        $('#confirmDelete').prop('disabled', true).text('Deleting...');
        $.ajax({
            url: baseUrl + '/delete-role',
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    toastr.error(response.message);
                    $('#confirmDelete').prop('disabled', false).text('Delete');
                }
            },
            error: function(xhr) {
                $('#confirmDelete').prop('disabled', false).text('Delete');
                toastr.error('Error occurred');
            }
        });
    });
});


// User Management Logic (Inside Role Blade)
$(document).ready(function() {
    var userModal = $('#userModal');

    $('#addUserBtn').on('click', function() {
        userModal.show();
    });

    $('.close-user, .close-user-modal').on('click', function() {
        userModal.hide();
    });

    $(window).on('click', function(event) {
        if ($(event.target).is(userModal)) {
            userModal.hide();
        }
    });

    $('#userForm').validate({
        rules: {
            user_name: "required",
            user_email: { required: true, email: true },
            user_role: "required",
            user_password: { required: true, minlength: 6 }
        },
        submitHandler: function(form) {
            $('#saveUserBtn').prop('disabled', true).text('Creating...');
            $.ajax({
                url: baseUrl + '/add-user',
                type: "POST",
                data: $(form).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() { location.reload(); }, 1000);
                    } else {
                        toastr.error(response.message);
                        $('#saveUserBtn').prop('disabled', false).text('Create User');
                    }
                },
                error: function(xhr) {
                    $('#saveUserBtn').prop('disabled', false).text('Create User');
                    var msg = 'Error occurred';
                    if(xhr.responseJSON && xhr.responseJSON.message){
                        msg = xhr.responseJSON.message;
                    }
                    toastr.error(msg);
                }
            });
        }
    });

    var editUserModal = $('#editUserModal');
    var deleteUserModal = $('#deleteUserModal');

    // Close User Modals
    $('.close-user-edit, .close-user-edit-modal').on('click', function() {
        editUserModal.hide();
    });
    $('.close-user-delete, .close-user-delete-modal').on('click', function() {
        deleteUserModal.hide();
    });

    $(window).on('click', function(event) {
        if ($(event.target).is(editUserModal)) {
            editUserModal.hide();
        }
        if ($(event.target).is(deleteUserModal)) {
            deleteUserModal.hide();
        }
    });

    // Edit User Click
    $(document).on('click', '.editUser', function() {
        var id = $(this).data('id');
        $.ajax({
            url: baseUrl + '/get-user-details/' + id,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    $('#userId').val(response.data.id);
                    $('#edit_user_name').val(response.data.name);
                    $('#edit_user_role').val(response.data.role_id);
                    editUserModal.show();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Error fetching details');
            }
        });
    });

    // Update User
    $('#editUserForm').validate({
        rules: {
            edit_user_name: "required",
            edit_user_role: "required"
        },
        submitHandler: function(form) {
            $('#updateUserBtn').prop('disabled', true).text('Updating...');
            $.ajax({
                url: baseUrl + '/update-user',
                type: "POST",
                data: $(form).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() { location.reload(); }, 1000);
                    } else {
                        toastr.error(response.message);
                        $('#updateUserBtn').prop('disabled', false).text('Update User');
                    }
                },
                error: function(xhr) {
                    $('#updateUserBtn').prop('disabled', false).text('Update User');
                    toastr.error('Error occurred');
                }
            });
        }
    });

    // Delete User Click
    $(document).on('click', '.deleteUser', function() {
        var id = $(this).data('id');
        $('#delUserId').val(id);
        deleteUserModal.show();
    });

    // Confirm Delete User
    $('#deleteUserForm').on('submit', function(e) {
        e.preventDefault();
        $('#confirmDeleteUser').prop('disabled', true).text('Deleting...');
        $.ajax({
            url: baseUrl + '/delete-user',
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    toastr.error(response.message);
                    $('#confirmDeleteUser').prop('disabled', false).text('Delete');
                }
            },
            error: function(xhr) {
                $('#confirmDeleteUser').prop('disabled', false).text('Delete');
                toastr.error('Error occurred');
            }
        });
    });
});

// Logout Logic
$('.logout').click(function(){
    
    $.ajax({
        url: baseUrl+'/logout',
        type: "GET",
        success: function(response){
            if(response.success == true){
                window.location.href = baseUrl;
            }else{
                toastr.error(response.message);   
            }
        },
        error: function(xhr, status, error){
            toastr.error(xhr.responseJSON.message);
        }
    });
});