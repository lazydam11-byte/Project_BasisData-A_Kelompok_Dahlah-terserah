<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('pelanggan')->check()) {
            return redirect()->route('katalog');
        }

        return view('auth.pelanggan-login');
    }

    public function login(Request $request)
    {
        $kredensial = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::guard('pelanggan')->attempt($kredensial)) {
            $request->session()->regenerate();

            return redirect()->intended(route('katalog'));
        }

        return back()
            ->withErrors(['login' => 'Username atau password tidak valid. Periksa kembali, lalu tekan Masuk untuk mencoba lagi.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('pelanggan')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('katalog');
    }
}
