<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function showKepalaTokoLoginForm()
    {
        return view('auth.login', ['role' => 'kepala_toko', 'title' => 'Kepala Toko Login']);
    }

    public function login(\Illuminate\Http\Request $request)
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

        if (\Illuminate\Support\Facades\Auth::attempt([$loginType => $input, 'password' => $request->password])) {
            $user = \Illuminate\Support\Facades\Auth::user();

            // Check if loop user role matches the requested login page role
            // If strictly enforcing that Admin MUST login at /login/admin:
            if ($request->has('role')) {
                $requiredRole = $request->role;
                if ($user->role->name !== $requiredRole) {
                    \Illuminate\Support\Facades\Auth::logout();
                    return back()->withErrors(['username' => 'Access denied: You are not a ' . ucfirst($requiredRole)]);
                }
            }

            $request->session()->regenerate();

            // Redirect based on role
            $role = $user->role->name;
            if ($role === 'admin' || $role === 'kepala_toko')
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

    public function logout(\Illuminate\Http\Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
