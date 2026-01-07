<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::all();
        $users = User::where('created_by', Auth::id())->with('role')->get();

        return view('roles', compact('roles', 'users'));
    }

    public function store(Request $request){
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name'
            ]);

            Role::create([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully'
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
            $role = Role::find($id);
            if($role){
                return response()->json([
                    'success' => true,
                    'data' => $role
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Not found']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                'edit_name' => 'required|string|max:255',
                'roleId' => 'required'
            ]);

            Role::where('id', $request->roleId)->update([
                'name' => $request->edit_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully'
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
            Role::where('id', $request->delRoleId)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeUser(Request $request){
        try {
            $request->validate([
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|unique:users,email',
                'user_role' => 'required|exists:roles,id',
                'user_password' => 'required|min:6'
            ]);

            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password),
                'role_id' => $request->user_role,
                'created_by' => Auth::id()
            ]);

            // Fetch role name for email
            $role = Role::find($request->user_role);
            $userData = $user->toArray();
            $userData['role_name'] = $role ? $role->name : 'N/A';

            // Send Email
            Mail::to($user->email)->send(new UserCreatedMail($userData, $request->user_password));

            return response()->json([
                'success' => true,
                'message' => 'User created and email sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserDetails($id){
        try {
            $user = User::where('id', $id)->where('created_by', Auth::id())->first();
            
            if($user){
                return response()->json([
                    'success' => true,
                    'data' => $user
                ]);
            }
            return response()->json(['success' => false, 'message' => 'User not found or unauthorized']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateUser(Request $request){
        try {
            $request->validate([
                'edit_user_name' => 'required|string|max:255',
                'edit_user_role' => 'required|exists:roles,id',
                'userId' => 'required'
            ]);
            
            $user = User::where('id', $request->userId)->where('created_by', Auth::id())->first();

            if(!$user){
                return response()->json(['success' => false, 'message' => 'User not found or unauthorized']);
            }

            $user->update([
                'name' => $request->edit_user_name,
                'role_id' => $request->edit_user_role
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser(Request $request){
        try {
            $user = User::where('id', $request->delUserId)->where('created_by', Auth::id())->first();

            if(!$user){
                 return response()->json(['success' => false, 'message' => 'User not found or unauthorized']);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
