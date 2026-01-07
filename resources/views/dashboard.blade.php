@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @include('partials.header', ['title' => 'Employees'])

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 header-actions">
            <h3>Employee List</h3>
            <button id="addEmployeeBtn" class="btn-primary">Add Employee</button>
        </div>

        <table id="employeeTable">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Salary</th>
                    <th>Joining Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->department }}</td>
                    <td>&#8377;{{ number_format($employee->salary, 2, '.', ',') }}</td>
                    <td>{{ date('d-m-Y', strtotime($employee->joining_date)) }}</td>
                    <td>
                        <a href="javascript:void(0)" class="edit" data-id="{{ $employee->emp_id }}"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" class="delete" style="color: red; margin-left: 10px;" data-id="{{ $employee->emp_id }}"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modals -->
    <!-- Add/Edit Modal -->
    <div id="employeeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Add Employee</h4>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required class="form-control" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" required class="form-control" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" id="position" name="position" required class="form-control" placeholder="Enter Position">
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" required class="form-control">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary</label>
                        <input type="text" id="salary" name="salary" required class="form-control" placeholder="Enter Salary">
                    </div>
                    <div class="form-group">
                        <label for="joining_date">Joining Date</label>
                        <input type="date" id="joining_date" name="joining_date" required class="form-control" placeholder="Enter Joining Date">
                    </div>
                    <div class="form-actions">
                         <button type="submit" id="addEmployeeSaveBtn" class="btn-submit">Save</button>
                         <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Edit Employee</h4>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="hidden" id="employeeId" name="employeeId">
                        <input type="text" id="edit_name" name="edit_name" required class="form-control" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="edit_email" name="edit_email" required class="form-control" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" id="edit_position" name="edit_position" required class="form-control" placeholder="Enter Position">
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="edit_department" name="edit_department" required class="form-control">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary</label>
                        <input type="text" id="edit_salary" name="edit_salary" required class="form-control" placeholder="Enter Salary">
                    </div>
                    <div class="form-group">
                        <label for="joining_date">Joining Date</label>
                        <input type="date" id="edit_joining_date" name="edit_joining_date" required class="form-control" placeholder="Enter Joining Date">
                    </div>
                    <div class="form-actions">
                         <button type="submit" id="editEmployeeBtn" class="btn-submit">Save</button>
                         <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
         <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h4>Delete Employee</h4>
                <span class="close-delete">&times;</span>
            </div>
            <div class="modal-body">
                <form id="deleteForm">
                    @csrf
                    <input type="hidden" id="delEmployeeId" name="delEmployeeId">
                    <p>Are you sure you want to delete this employee?</p>
                    <div class="form-actions">
                        <button type="submit" id="confirmDelete" class="btn-danger">Delete</button>
                        <button type="button" class="btn-cancel close-modal">Cancel</button>
                    </div>
                </form>
            </div>
         </div>
    </div>

@endsection