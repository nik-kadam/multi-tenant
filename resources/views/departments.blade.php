@extends('layouts.app')

@section('title', 'Departments')

@section('content')
    @include('partials.header', ['title' => 'Departments'])

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 header-actions">
            <h3>Department List</h3>
            <button id="addDepartmentBtn" class="btn-primary">Add Department</button>
        </div>

        <table id="departmentTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $department->name }}</td>
                    <td>{{ $department->created_at->format('d-m-Y') }}</td>
                    <td>
                        <a href="javascript:void(0)" class="editDepartment" data-id="{{ $department->id }}"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" class="deleteDepartment" style="color: red; margin-left: 10px;" data-id="{{ $department->id }}"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Department Modal -->
    <div id="departmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Add Department</h4>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="departmentForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Department Name</label>
                        <input type="text" id="name" name="name" required class="form-control" placeholder="Enter Department Name">
                    </div>
                    <div class="form-actions">
                         <button type="submit" id="saveDepartmentBtn" class="btn-submit">Save</button>
                         <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div id="editDepartmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Edit Department</h4>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editDepartmentForm">
                    @csrf
                    <input type="hidden" id="departmentId" name="departmentId">
                    <div class="form-group">
                        <label for="edit_name">Department Name</label>
                        <input type="text" id="edit_name" name="edit_name" required class="form-control" placeholder="Enter Department Name">
                    </div>
                    <div class="form-actions">
                         <button type="submit" id="updateDepartmentBtn" class="btn-submit">Update</button>
                         <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Department Modal -->
    <div id="deleteDepartmentModal" class="modal">
         <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h4>Delete Department</h4>
                <span class="close-delete">&times;</span>
            </div>
            <div class="modal-body">
                <form id="deleteDepartmentForm">
                    @csrf
                    <input type="hidden" id="delDepartmentId" name="delDepartmentId">
                    <p>Are you sure you want to delete this department?</p>
                    <div class="form-actions">
                        <button type="submit" id="confirmDelete" class="btn-danger">Delete</button>
                        <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
         </div>
    </div>

@endsection
