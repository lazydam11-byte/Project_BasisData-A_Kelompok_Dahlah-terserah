<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts._head')
    <title>Portal Pegawai · Gadgetkuori</title>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-br from-brand-soft to-white font-sans text-slate-800">
    <header class="bg-brand text-white">
        <div class="mx-auto max-w-7xl px-4 py-3 flex items-center gap-6">
            <a href="{{ route('katalog') }}" class="font-display font-bold text-lg">Gadgetkuori.com</a>
            <span class="text-sm">Sistem Informasi Toko</span>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-lg sm:p-8">
            <h1 class="text-center font-display text-2xl font-bold">Portal Pegawai</h1>
            <p class="mt-1 text-center text-sm text-slate-500">Masuk untuk mengakses sistem informasi toko</p>
            <hr class="my-5">

            @error('login')
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                    <p class="font-semibold">Login Gagal</p>
                    <p>{{ $message }}</p>
                </div>
            @enderror

            <form method="POST" action="{{ route('pegawai.login.proses') }}" class="space-y-4" novalidate>
                @csrf
                <div>
                    <label for="username" class="mb-1 block text-sm font-semibold">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" autofocus
                           placeholder="Masukkan username Anda"
                           class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand @error('username') border-red-400 @else border-slate-300 @enderror">
                    @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-semibold">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password"
                           placeholder="Masukkan password Anda"
                           class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand @error('password') border-red-400 @else border-slate-300 @enderror">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full rounded-lg bg-brand py-2.5 font-semibold text-white hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">Masuk</button>
            </form>

            <p class="mt-6 text-center text-xs text-slate-400">Hubungi administrator jika lupa kredensial</p>
        </div>
    </main>
</body>
</html>
