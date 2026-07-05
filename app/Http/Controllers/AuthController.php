<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('layouts.auth.login');
    }

    public function loginproses(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return redirect()->back()->withInput()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
