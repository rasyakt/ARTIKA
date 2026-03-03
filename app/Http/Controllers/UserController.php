<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\IdentityType;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UserTemplateExport;
use Illuminate\Support\Facades\Auth; // Added for Auth::id()

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
        $identityTypes = IdentityType::where('is_active', true)->get();

        return view('admin.users.index', compact('users', 'roles', 'identityTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'nis' => 'nullable|string',
            'identity_type_id' => 'nullable|exists:identity_types,id',
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
            'identity_type_id' => $request->identity_type_id,
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
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'nis' => 'nullable|string',
            'identity_type_id' => 'nullable|exists:identity_types,id',
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
            'identity_type_id' => $request->identity_type_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role->name === 'superadmin') { // Changed 'Superadmin' to 'superadmin' for consistency
            return redirect()->route('user.index')
                ->with('error', 'Akun Superadmin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Download the Excel template for importing users.
     */
    public function downloadTemplate()
    {
        return Excel::download(new UserTemplateExport, 'Template_Import_User.xlsx');
    }

    /**
     * Handle the Excel file import.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'], // Max 5MB
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));

            return redirect()->route('admin.users')->with('success', 'Berhasil mengimpor data user secara massal dari file Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Gagal import, format file salah pada baris: ';

            foreach ($failures as $failure) {
                $errorMsg .= $failure->row() . ' (' . implode(', ', $failure->errors()) . ')<br>';
            }

            return redirect()->route('admin.users')->with('error', $errorMsg);

        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Terjadi kesalahan saat memproses file Excel: ' . $e->getMessage());
        }
    }
}
