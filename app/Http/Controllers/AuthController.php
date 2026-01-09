<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth.login');
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
            $request->session()->regenerate();

            // Redirect based on role
            // Since we haven't set up role redirection yet, just go to dashboard
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
