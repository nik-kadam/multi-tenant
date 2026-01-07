<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        // Roles are global in this app context, or we can filter if needed
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json(['message' => 'Role created', 'data' => $role], 201);
    }

    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) return response()->json(['message' => 'Role not found'], 404);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) return response()->json(['message' => 'Role not found'], 404);

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $role->update(['name' => $request->name]);

        return response()->json(['message' => 'Role updated', 'data' => $role]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) return response()->json(['message' => 'Role not found'], 404);

        $role->delete();

        return response()->json(['message' => 'Role deleted']);
    }
}
