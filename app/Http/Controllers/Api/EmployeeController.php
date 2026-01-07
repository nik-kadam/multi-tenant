<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    // Ensure tenant isolation
    private function getTenantEmployees() {
        return Employee::where('user_id', Auth::id());
    }

    public function index()
    {
        $employees = $this->getTenantEmployees()->get();
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'position' => 'required|string',
            'department' => 'required|string',
            'salary' => 'required|numeric',
            'joining_date' => 'required|date',
        ]);

        // Check uniqueness within tenant
        if($this->getTenantEmployees()->where('email', $request->email)->exists()) {
             return response()->json(['message' => 'Email already exists for this tenant'], 422);
        }

        $employee = Employee::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'department' => $request->department,
            'salary' => $request->salary,
            'joining_date' => $request->joining_date,
        ]);

        return response()->json(['message' => 'Employee created', 'data' => $employee], 201);
    }

    public function show($id)
    {
        $employee = $this->getTenantEmployees()->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        $employee = $this->getTenantEmployees()->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'position' => 'sometimes|string',
            'department' => 'sometimes|string', // Should ideally confirm dept exists
            'salary' => 'sometimes|numeric',
            'joining_date' => 'sometimes|date',
        ]);

        if($request->has('email') && $request->email !== $employee->email) {
             if($this->getTenantEmployees()->where('email', $request->email)->exists()) {
                 return response()->json(['message' => 'Email already exists for this tenant'], 422);
             }
        }

        $employee->update($request->all());

        return response()->json(['message' => 'Employee updated', 'data' => $employee]);
    }

    public function destroy($id)
    {
        $employee = $this->getTenantEmployees()->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $employee->delete();

        return response()->json(['message' => 'Employee deleted']);
    }
}
