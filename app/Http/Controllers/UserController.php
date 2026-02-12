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
        $currentUser = auth()->user();
        $query = User::with(['role']);

        if ($currentUser->role->name === 'superadmin') {
            // Superadmin sees everyone except themselves
            $query->where('id', '!=', $currentUser->id);
        } else {
            // Admin only sees warehouse and cashier
            $query->whereHas('role', function ($q) {
                $q->whereNotIn('name', ['superadmin', 'admin']);
            });
        }

        $users = $query->latest()->paginate(10);

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

        // Security check: only superadmin can create admins/warehouse
        $currentUser = auth()->user();
        $targetRole = Role::findOrFail($request->role_id);

        if ($currentUser->role->name !== 'superadmin' && in_array($targetRole->name, ['superadmin', 'admin'])) {
            return redirect()->back()->with('error', 'Only Manager, Warehouse or Cashier accounts can be added!');
        }

        if ($targetRole->name === 'superadmin') {
            return redirect()->back()->with('error', 'Cannot create more Superadmin accounts!');
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

        $currentUser = auth()->user();

        // Security check: cannot edit superior or same-level accounts if not superadmin
        if ($currentUser->role->name !== 'superadmin') {
            if (in_array($user->role->name, ['superadmin', 'admin'])) {
                return redirect()->route('admin.users')->with('error', 'Cannot edit administrator accounts!');
            }
        }

        // Cannot edit yourself here (prevents locking yourself out of your own role)
        if ($user->id === $currentUser->id) {
            return redirect()->route('admin.users')->with('error', 'Manage your profile in settings!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'nis' => 'nullable|string|unique:users,nis,' . $id,
        ]);

        // Security check: cannot change role to superadmin
        $newRole = Role::findOrFail($request->role_id);
        if ($newRole->name === 'superadmin') {
            return redirect()->back()->with('error', 'Invalid role selection!');
        }

        // Admin cannot promote to admin
        if ($currentUser->role->name !== 'superadmin' && $newRole->name === 'admin') {
            return redirect()->back()->with('error', 'Insufficient permissions!');
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

        // Security check: only superadmin can delete admin/warehouse
        $currentUser = auth()->user();
        if ($currentUser->role->name !== 'superadmin' && in_array($user->role->name, ['superadmin', 'admin'])) {
            return redirect()->route('admin.users')->with('error', 'Only cashier accounts can be deleted!');
        }

        if ($user->role->name === 'superadmin') {
            return redirect()->route('admin.users')->with('error', 'Cannot delete the Superadmin!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
}
