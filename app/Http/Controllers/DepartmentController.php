<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index(){
        $departments = Department::where('user_id', Auth::id())->get();
        return view('departments', compact('departments'));
    }

    public function store(Request $request){
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            Department::create([
                'user_id' => Auth::id(),
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getDetails($id){
        try {
            $department = Department::where(['id' => $id, 'user_id' => Auth::id()])->first();
            if($department){
                return response()->json([
                    'success' => true,
                    'data' => $department
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                'edit_name' => 'required|string|max:255',
                'departmentId' => 'required'
            ]);

            Department::where(['id' => $request->departmentId, 'user_id' => Auth::id()])->update([
                'name' => $request->edit_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request){
        try {
            $department = Department::where(['id' => $request->delDepartmentId, 'user_id' => Auth::id()])->first();
            
            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found'
                ], 404);
            }
            
            // Check if any employees are assigned to this department
            $employeeCount = Employee::where('department', $department->name)
                ->where(function($query) {
                    $currentUser = Auth::user();
                    if (is_null($currentUser->created_by)) {
                        $subUserIds = User::where('created_by', $currentUser->id)->pluck('id')->toArray();
                        $subUserIds[] = $currentUser->id;
                        $query->whereIn('user_id', $subUserIds);
                    } else {
                        $tenantOwnerId = $currentUser->created_by;
                        $query->where('user_id', $tenantOwnerId)
                              ->orWhere('user_id', $currentUser->id);
                    }
                })
                ->count();
            
            if ($employeeCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete department. {$employeeCount} employee(s) are assigned to this department."
                ]);
            }
            
            $department->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
