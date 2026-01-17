<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Only show non-admin users
        $users = User::with(['role'])
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->latest()
            ->paginate(10);

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'nis' => 'nullable|string|unique:users',
        ]);

        // Security check: only cashier accounts can be created
        $role = Role::findOrFail($request->role_id);
        if ($role->name !== 'cashier') {
            return redirect()->back()->with('error', 'Only cashier accounts can be added!');
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'nis' => $request->nis,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Security check: cannot edit an admin
        if ($user->role->name === 'admin') {
            return redirect()->route('admin.users')->with('error', 'Cannot edit administrator accounts!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'nis' => 'nullable|string|unique:users,nis,' . $id,
        ]);

        // Security check: cannot change role to admin or warehouse (warehouse can be edited but not added or switched to)
        $newRole = Role::findOrFail($request->role_id);
        if ($newRole->name === 'admin' || ($newRole->name === 'warehouse' && $user->role->name !== 'warehouse')) {
            return redirect()->back()->with('error', 'Invalid role selection!');
        }

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'role_id' => $request->role_id,
            'nis' => $request->nis,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Cannot delete your own account!');
        }

        // Security check: only cashier accounts can be deleted
        if ($user->role->name !== 'cashier') {
            return redirect()->route('admin.users')->with('error', 'Only cashier accounts can be deleted!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
}
