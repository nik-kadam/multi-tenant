<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Http\Requests\AddEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class UserController extends Controller
{

    public function dashboard(){
        $currentUser = Auth::user();
        
        $tenantOwnerId = $currentUser->created_by ?? $currentUser->id;
        
        if (is_null($currentUser->created_by)) {
            
            $subUserIds = User::where('created_by', $currentUser->id)->pluck('id')->toArray();
            
            $employees = Employee::where(function($query) use ($currentUser, $subUserIds) {
                $query->where('user_id', $currentUser->id)
                      ->orWhereIn('user_id', $subUserIds);
            })->get();
        } else {
            $employees = Employee::where(function($query) use ($currentUser, $tenantOwnerId) {
                $query->where('user_id', $tenantOwnerId)
                      ->orWhere('user_id', $currentUser->id);
            })->get();
        }
        
        $departments = Department::where('user_id', $tenantOwnerId)->get();
        return view('dashboard', compact('employees', 'departments'));
    }

    public function getEmployeeDetails($id){
        try {
            $employee = Employee::where('emp_id', $id)->first();
            return response()->json([
                'success' => true,
                'message' => 'Employee details fetched successfully',
                'data' => $employee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkEmail(Request $request){
        try {
            $email = $request->email;
            $currentUser = Auth::user();
            
            $tenantOwnerId = $currentUser->created_by ?? $currentUser->id;
            
            if (is_null($currentUser->created_by)) {
                
                $subUserIds = User::where('created_by', $currentUser->id)->pluck('id')->toArray();
                $subUserIds[] = $currentUser->id;
                
                $checkEmail = Employee::whereIn('user_id', $subUserIds)
                    ->where('email', $email)
                    ->first();
            } else {
                $checkEmail = Employee::where(function($query) use ($tenantOwnerId, $currentUser, $email) {
                    $query->where('user_id', $tenantOwnerId)
                          ->orWhere('user_id', $currentUser->id);
                })
                ->where('email', $email)
                ->first();
            }
            
            if($checkEmail){
                echo 'false'; // Email exists
            }else{
                echo 'true'; // Email is available
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function addEmployee(AddEmployeeRequest $request){
        try {
            $validateEmp = $request->validated();

            if($validateEmp){
                
                $addEmp = Employee::create([
                    'user_id' => Auth::user()->id,
                    'name' => $validateEmp['name'],
                    'email' => $validateEmp['email'],
                    'position' => $validateEmp['position'],
                    'department' => $validateEmp['department'],
                    'salary' => $validateEmp['salary'],
                    'joining_date' => $validateEmp['joining_date']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Employee added successfully'
                ]);

            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateEmployee(UpdateEmployeeRequest $request){
        try {
            $validateEmp = $request->validated();
            
            if($validateEmp){
                
                $updateEmp = Employee::where('emp_id', $request->employeeId)->update([
                    'name' => $validateEmp['edit_name'],
                    'email' => $validateEmp['edit_email'],
                    'position' => $validateEmp['edit_position'],
                    'department' => $validateEmp['edit_department'],
                    'salary' => $validateEmp['edit_salary'],
                    'joining_date' => $validateEmp['edit_joining_date']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Employee updated successfully'
                ]);

            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteEmployee(Request $request){
        try {
            $delEmp = Employee::where('emp_id', $request->delEmployeeId)->delete();
            if($delEmp){
                return response()->json([
                    'success' => true,
                    'message' => 'Employee deleted successfully'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout(){
        try {
            Auth::logout();
            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
