<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts._head')
    <title>Masuk · Gadgetkuori.com</title>
</head>
<body class="min-h-screen flex flex-col bg-brand-soft/40 font-sans text-slate-800">
    <header class="bg-brand text-white">
        <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
            <a href="{{ route('katalog') }}" class="font-display font-bold text-lg">Gadgetkuori.com</a>
            <a href="{{ route('katalog') }}" class="rounded border border-white/60 px-3 py-1.5 text-sm hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">Kembali ke beranda</a>
        </div>
    </header>

    <main class="flex-1 mx-auto grid w-full max-w-6xl items-center gap-10 px-4 py-10 lg:grid-cols-2">
        <section>
            <h1 class="font-display text-4xl font-bold leading-tight sm:text-5xl">Gadget pilihan Anda,<br>lebih dekat dari sini.</h1>
            <p class="mt-4 max-w-md text-slate-600">Jelajahi produk, bandingkan harga, dan cek stok sebelum datang ke Gadgetkuori.com.</p>
            <p class="mt-8 font-semibold">Lihat daring. Beli langsung di toko.</p>
            <p class="text-sm text-slate-500">Transaksi dan pembayaran dibantu oleh pegawai.</p>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-lg sm:p-8">
            <p class="text-xs font-semibold text-brand">Akun pembeli</p>
            <h2 class="font-display text-2xl font-bold">Selamat datang kembali</h2>
            <p class="mt-1 text-sm text-slate-500">Masuk dengan akun pembeli Anda untuk melanjutkan ke beranda.</p>

            @error('login')
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('login.proses') }}" class="mt-5 space-y-4" novalidate>
                @csrf
                <div>
                    <label for="username" class="mb-1 block text-sm font-semibold">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username"
                           placeholder="Masukkan username Anda"
                           class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand @error('username') border-red-400 @else border-slate-300 @enderror">
                    @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-semibold">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="current-password"
                               placeholder="Masukkan password Anda"
                               class="w-full rounded-lg border px-3 py-2.5 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-brand @error('password') border-red-400 @else border-slate-300 @enderror">
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-3 text-xs font-semibold text-brand">Tampilkan</button>
                    </div>
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full rounded-lg bg-brand py-2.5 font-semibold text-white hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">Masuk</button>
            </form>

            <div class="my-4 flex items-center gap-3 text-xs text-slate-400"><hr class="flex-1"><span>atau</span><hr class="flex-1"></div>
            <a href="{{ route('katalog') }}" class="block w-full rounded-lg border border-brand py-2.5 text-center font-semibold text-brand hover:bg-brand-soft focus:outline-none focus:ring-2 focus:ring-brand">Lanjut sebagai tamu</a>
            <p class="mt-2 text-center text-xs text-slate-500">Katalog dan informasi stok dapat dilihat tanpa masuk.</p>
        </section>
    </main>

    <footer class="bg-brand text-white/90 text-xs">
        <div class="mx-auto max-w-7xl px-4 py-4">© 2026 Gadgetkuori</div>
    </footer>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            var input = document.getElementById('password');
            var tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            this.textContent = tampil ? 'Sembunyikan' : 'Tampilkan';
        });
    </script>
</body>
</html>
