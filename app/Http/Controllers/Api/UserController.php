<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;

class UserController extends Controller
{
    private function getTenantUsers() {
        return User::where('created_by', Auth::id())->with('role');
    }

    public function index()
    {
        $users = $this->getTenantUsers()->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'created_by' => Auth::id()
        ]);

        // Send Email
        $role = Role::find($request->role_id);
        $userData = $user->toArray();
        $userData['role_name'] = $role ? $role->name : 'N/A';
        
        try {
            Mail::to($user->email)->send(new UserCreatedMail($userData, $request->password));
        } catch (\Exception $e) {
            // Log email failure but don't fail the request
        }

        return response()->json(['message' => 'User created', 'data' => $user], 201);
    }

    public function show($id)
    {
        $user = $this->getTenantUsers()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found or unauthorized'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = $this->getTenantUsers()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found or unauthorized'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'role_id' => 'sometimes|exists:roles,id',
        ]);

        $user->update($request->only(['name', 'role_id']));

        return response()->json(['message' => 'User updated', 'data' => $user]);
    }

    public function destroy($id)
    {
        $user = $this->getTenantUsers()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found or unauthorized'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
