<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth.login', ['role' => 'cashier', 'title' => 'Cashier Login']);
    }

    public function showAdminLoginForm()
    {
        return view('auth.login', ['role' => 'admin', 'title' => 'Admin Login']);
    }

    public function showWarehouseLoginForm()
    {
        return view('auth.login', ['role' => 'warehouse', 'title' => 'Warehouse Login']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $input = $request->username;
        $loginType = 'username';

        // Check if input looks like NIS (numeric and e.g. > 3 digits)
        if (is_numeric($input)) {
            $loginType = 'nis';
        }

        if (Auth::attempt([$loginType => $input, 'password' => $request->password])) {
            $user = Auth::user();

            // Check if loop user role matches the requested login page role
            // If strictly enforcing that Admin MUST login at /login/admin:
            if ($request->has('role')) {
                $requiredRole = $request->role;
                $userRole = ($user && $user->role) ? $user->role->name : null;

                if (!$userRole) {
                    Auth::logout();
                    return back()->withErrors(['username' => 'Profil pengguna tidak valid atau role tidak ditemukan.']);
                }

                // Superadmins and managers can login at the admin login page
                $isAuthorized = ($userRole === $requiredRole) ||
                    ($requiredRole === 'admin' && in_array(strtolower($userRole), ['superadmin', 'manager']));

                if (!$isAuthorized) {
                    Auth::logout();
                    return back()->withErrors(['username' => 'Akses ditolak: Anda bukan ' . ucfirst($requiredRole)]);
                }
            }

            $request->session()->regenerate();

            // Redirect based on role (case-insensitive)
            $userRole = ($user && $user->role) ? strtolower($user->role->name) : 'user';
            if ($userRole === 'superadmin' || $userRole === 'admin')
                return redirect()->route('admin.dashboard');
            if ($userRole === 'manager')
                return redirect()->route('manager.dashboard');
            if ($userRole === 'cashier')
                return redirect()->route('pos.index');
            if ($userRole === 'warehouse')
                return redirect()->route('warehouse.dashboard');

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Login gagal! Periksa Username/NIS dan Password.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
