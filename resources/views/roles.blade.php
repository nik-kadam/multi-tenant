@extends('layouts.app')

@section('title', 'RBAC')

@section('content')
    @include('partials.header', ['title' => 'RBAC'])

    <div class="container">
        
        <!-- Roles Section -->
        <div class="d-flex justify-content-between align-items-center mb-3 header-actions">
            <h3>Roles List</h3>
            {{-- <button id="addRoleBtn" class="btn-primary">Add Role</button> --}}
        </div>

        <table id="roleTable" class="table table-striped mb-5">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Name</th>
                    <th>Created At</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->created_at->format('d-m-Y') }}</td>
                    {{-- <td>
                        <a href="javascript:void(0)" class="editRole" data-id="{{ $role->id }}"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" class="deleteRole" style="color: red; margin-left: 10px;" data-id="{{ $role->id }}"><i class="fa fa-trash"></i></a>
                    </td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(auth()->user()->isAdmin())
        <!-- Users Section -->
        <div class="d-flex justify-content-between align-items-center mb-3 header-actions" style="margin-top: 50px;">
            <h3>User Management</h3>
            <button id="addUserBtn" class="btn-primary" style="background: linear-gradient(135deg, #1d6f42 0%, #155c35 100%);">Add User</button>
        </div>

        <table id="userTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role ? $user->role->name : 'N/A' }}</td>
                    <td>{{ $user->created_at->format('d-m-Y') }}</td>
                    <td>
                        <a href="javascript:void(0)" class="editUser" data-id="{{ $user->id }}"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" class="deleteUser" style="color: red; margin-left: 10px;" data-id="{{ $user->id }}"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Add Role Modal -->
    <div id="roleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Add Role</h4>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="roleForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Role Name</label>
                        <input type="text" id="name" name="name" required class="form-control" placeholder="Enter Role Name">
                    </div>
                    <div class="form-actions">
                         <button type="submit" id="saveRoleBtn" class="btn-submit">Save</button>
                         <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Edit Role</h4>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    @csrf
                    <input type="hidden" id="roleId" name="roleId">
                    <div class="form-group">
                        <label for="edit_name">Role Name</label>
                        <input type="text" id="edit_name" name="edit_name" required class="form-control" placeholder="Enter Role Name">
                    </div>
                    <div class="form-actions">
                         <button type="submit" id="updateRoleBtn" class="btn-submit">Update</button>
                         <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Role Modal -->
    <div id="deleteRoleModal" class="modal">
         <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h4>Delete Role</h4>
                <span class="close-delete">&times;</span>
            </div>
            <div class="modal-body">
                <form id="deleteRoleForm">
                    @csrf
                    <input type="hidden" id="delRoleId" name="delRoleId">
                    <p>Are you sure you want to delete this role?</p>
                    <div class="form-actions">
                        <button type="submit" id="confirmDelete" class="btn-danger">Delete</button>
                        <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
         </div>
    </div>


    <!-- Add User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Add User</h4>
                <span class="close-user">&times;</span>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    @csrf
                    <div class="form-group">
                        <label for="user_name">Name</label>
                        <input type="text" id="user_name" name="user_name" required class="form-control" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="email" id="user_email" name="user_email" required class="form-control" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <label for="user_role">Role</label>
                        <select id="user_role" name="user_role" required class="form-control">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" id="user_password" name="user_password" required class="form-control" placeholder="Enter Password">
                    </div>
                    <div class="form-actions">
                         <button type="submit" id="saveUserBtn" class="btn-submit">Create User</button>
                         <button type="button" class="btn-cancel close-user-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Edit User</h4>
                <span class="close-user-edit">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    <input type="hidden" id="userId" name="userId">
                    <div class="form-group">
                        <label for="edit_user_name">Name</label>
                        <input type="text" id="edit_user_name" name="edit_user_name" required class="form-control" placeholder="Enter Name">
                    </div>
                    {{-- Email usually not editable or handled carefully --}}
                    {{-- <div class="form-group">
                        <label for="edit_user_email">Email</label>
                        <input type="email" id="edit_user_email" name="edit_user_email" required class="form-control" placeholder="Enter Email" readonly>
                    </div> --}}
                    <div class="form-group">
                        <label for="edit_user_role">Role</label>
                        <select id="edit_user_role" name="edit_user_role" required class="form-control">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-actions">
                         <button type="submit" id="updateUserBtn" class="btn-submit">Update User</button>
                         <button type="button" class="btn-cancel close-user-edit-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div id="deleteUserModal" class="modal">
         <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h4>Delete User</h4>
                <span class="close-user-delete">&times;</span>
            </div>
            <div class="modal-body">
                <form id="deleteUserForm">
                    @csrf
                    <input type="hidden" id="delUserId" name="delUserId">
                    <p>Are you sure you want to delete this user?</p>
                    <div class="form-actions">
                        <button type="submit" id="confirmDeleteUser" class="btn-danger">Delete</button>
                        <button type="button" class="btn-cancel close-user-delete-modal">Cancel</button>
                    </div>
                </form>
            </div>
         </div>
    </div>

@endsection
