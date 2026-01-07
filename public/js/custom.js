$(document).ready(function(){

    // Initialize DataTable
    var table = $('#employeeTable, #departmentTable, #roleTable, #userTable').DataTable(
        {
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2 ] 
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2 ] 
                    }
                },
            ]
        }
    );

    // Modal Elements
    var modal = $('#employeeModal');
    var editModal = $('#editModal');
    var deleteModal = $('#deleteModal');

    // Add Employee Button
    $('#addEmployeeBtn').click(function() {
        modal.show();
        modal.css('display', 'block'); 
    });

    // Edit Employee 
    $(document).on('click', '.edit', function() {
       
        var id = $(this).data('id'); 
        
        $.ajax({
                url: baseUrl+'/get-employee-details/'+id,
                type: "GET",
                success: function(response){               
                    if(response.success == true){
                        $('#employeeId').val(response.data.emp_id);
                        $('#edit_name').val(response.data.name);
                        $('#edit_email').val(response.data.email);
                        $('#edit_position').val(response.data.position);
                        $('#edit_department').val(response.data.department);
                        $('#edit_salary').val(response.data.salary);
                        $('#edit_joining_date').val(moment(response.data.joining_date).format("YYYY-MM-DD"));
                        $('#editModal').show();
                        $('#editModal').css('display', 'block');
                    }else{
                        toastr.error(response.message);   
                    }
                },
                error: function(xhr, status, error){
                    toastr.error(xhr.responseJSON.message);
                }
            });
    });

    // Delete Employee
    $(document).on('click', '.delete', function() {

        var id = $(this).data('id'); 
        $('#delEmployeeId').val(id);

        deleteModal.show();
        deleteModal.css('display', 'block');
    });

    // Close Modals
    $('.close, .close-modal, .close-delete').click(function() {
        modal.hide();
        editModal.hide();
        deleteModal.hide();
    });

    // Window Click to close
    $(window).click(function(event) {
        if ($(event.target).is(modal) || $(event.target).is(editModal)) {
            modal.hide();
            editModal.hide();
        }
        if ($(event.target).is(deleteModal)) {
            deleteModal.hide();
        }
    });
    
});