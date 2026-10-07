<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attemptData = [
            $fieldType => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($attemptData, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'Aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['login' => 'Akun Anda berstatus non-aktif. Silakan hubungi administrator.']);
            }

            ActivityLog::log('Login', 'Pengguna berhasil masuk ke dalam sistem');

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'login' => 'Username/Email atau password yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::log('Logout', 'Pengguna keluar dari sistem');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
