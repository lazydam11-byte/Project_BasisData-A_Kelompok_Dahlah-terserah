<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PelangganRegisterController extends Controller
{
    public function showRegisterForm()
    {
        if (Auth::guard('pelanggan')->check()) {
            return redirect()->route('katalog.index');
        }

        return view('auth.pelanggan-daftar');
    }

    public function register(Request $request)
    {
        // Email disimpan huruf kecil agar login tidak bergantung pada kapitalisasi.
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:pelanggan,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'max' => ':attribute tidak boleh melebihi :max karakter.',
            'unique' => 'Email ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ], [
            'nama' => 'Nama lengkap',
            'email' => 'Email',
            'password' => 'Password',
        ]);

        // Password otomatis di-hash oleh cast 'hashed' pada model Pelanggan.
        $pelanggan = Pelanggan::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::guard('pelanggan')->login($pelanggan);
        $request->session()->regenerate();

        return redirect()->route('katalog.index')->with('success', 'Registrasi berhasil. Selamat datang di Gadgetkuori.com!');
    }
}
