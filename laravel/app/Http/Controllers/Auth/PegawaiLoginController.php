<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('pegawai')->check()) {
            return redirect()->route('admin.produk.index');
        }

        return view('auth.pegawai-login');
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

        if (Auth::guard('pegawai')->attempt($kredensial)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.produk.index'));
        }

        return back()
            ->withErrors(['login' => 'Username atau password tidak valid. Silakan coba lagi.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('pegawai')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('pegawai.login');
    }
}
