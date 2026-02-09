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
                $userRole = $user->role->name;

                // Superadmins can login anywhere that requires 'admin' role
                $isAuthorized = ($userRole === $requiredRole) ||
                    ($requiredRole === 'admin' && $userRole === 'superadmin');

                if (!$isAuthorized) {
                    Auth::logout();
                    return back()->withErrors(['username' => 'Access denied: You are not a ' . ucfirst($requiredRole)]);
                }
            }

            $request->session()->regenerate();

            // Redirect based on role
            $role = $user->role->name;
            if ($role === 'superadmin' || $role === 'admin')
                return redirect()->route('admin.dashboard');
            if ($role === 'cashier')
                return redirect()->route('pos.index');
            if ($role === 'warehouse')
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
