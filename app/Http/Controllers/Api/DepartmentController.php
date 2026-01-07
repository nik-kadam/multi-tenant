<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    private function getTenantDepartments() {
        return Department::where('user_id', Auth::id());
    }

    public function index()
    {
        $departments = $this->getTenantDepartments()->get();
        return response()->json($departments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        if($this->getTenantDepartments()->where('name', $request->name)->exists()) {
            return response()->json(['message' => 'Department already exists'], 422);
        }

        $department = Department::create([
            'user_id' => Auth::id(),
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Department created', 'data' => $department], 201);
    }

    public function show($id)
    {
        $department = $this->getTenantDepartments()->find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        return response()->json($department);
    }

    public function update(Request $request, $id)
    {
        $department = $this->getTenantDepartments()->find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        if($this->getTenantDepartments()->where('name', $request->name)->where('id', '!=', $id)->exists()) {
             return response()->json(['message' => 'Department name already taken'], 422);
        }

        $department->update([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Department updated', 'data' => $department]);
    }

    public function destroy($id)
    {
        $department = $this->getTenantDepartments()->find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        // Check if any employees are assigned to this department
        $employeeCount = \App\Models\Employee::where('department', $department->name)
            ->where(function($query) {
                $currentUser = Auth::user();
                if (is_null($currentUser->created_by)) {
                    // Tenant: check their employees + sub-users' employees
                    $subUserIds = \App\Models\User::where('created_by', $currentUser->id)->pluck('id')->toArray();
                    $subUserIds[] = $currentUser->id;
                    $query->whereIn('user_id', $subUserIds);
                } else {
                    // Sub-user: check tenant's + their own employees
                    $tenantOwnerId = $currentUser->created_by;
                    $query->where('user_id', $tenantOwnerId)
                          ->orWhere('user_id', $currentUser->id);
                }
            })
            ->count();
        
        if ($employeeCount > 0) {
            return response()->json([
                'message' => "Cannot delete department. {$employeeCount} employee(s) are assigned to this department."
            ], 422);
        }

        $department->delete();

        return response()->json(['message' => 'Department deleted']);
    }
}
