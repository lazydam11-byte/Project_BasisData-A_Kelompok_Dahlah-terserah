@php $pegawai = auth('pegawai')->user(); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts._head')
    <title>@yield('title', 'Sistem Informasi Toko') · Gadgetkuori</title>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800 font-sans">
    <header class="bg-brand text-white">
        <div class="mx-auto max-w-7xl px-4 py-3 flex flex-wrap items-center gap-x-6 gap-y-2">
            <a href="{{ route('admin.dashboard') }}" class="font-display font-bold text-lg">Gadgetkuori.com</a>
            <nav class="flex gap-1 text-sm">
                <a href="{{ route('admin.produk.index') }}" class="rounded px-3 py-1.5 bg-brand-dark">Data Master</a>
            </nav>
            <div class="ml-auto flex items-center gap-3 text-sm">
                <div class="text-right leading-tight">
                    <div class="font-semibold">{{ $pegawai->nama }}</div>
                    <div class="text-xs text-white/80">{{ $pegawai->jabatan }}</div>
                </div>
                <form method="POST" action="{{ route('pegawai.logout') }}">
                    @csrf
                    <button type="submit" class="rounded border border-white/60 px-3 py-1.5 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 mx-auto w-full max-w-7xl px-4 py-6">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-brand-soft px-4 py-3 text-sm text-brand-dark" role="status">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-brand text-white/90 text-xs">
        <div class="mx-auto max-w-7xl px-4 py-4">Gadgetkuori · Sistem Informasi Toko Internal</div>
    </footer>
</body>
</html>
