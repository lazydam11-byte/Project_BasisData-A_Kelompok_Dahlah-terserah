@php
    $pegawai = auth('pegawai')->user();
    $pesanSukses = session('success') ?? session('sukses');

    // Jumlah pesanan yang menunggu diproses, hanya dihitung bila pegawai boleh mengelola transaksi
    $adaAksesTransaksi = $pegawai->bisaKelolaTransaksi();
    $pesananMenunggu = $adaAksesTransaksi
        ? \App\Models\TransaksiPenjualan::where('status_pembayaran', \App\Models\TransaksiPenjualan::BELUM_DIBAYAR)->count()
        : 0;
@endphp
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
            <nav class="flex flex-wrap gap-1 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                    class="rounded px-3 py-1.5 text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-brand-dark' : 'hover:bg-brand-dark/60' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.produk.index') }}" class="rounded px-3 py-1.5 {{ request()->routeIs('admin.produk.*') ? 'bg-brand-dark' : 'hover:bg-white/10' }}">Produk</a>
                <a href="{{ route('admin.kategori.index') }}" class="rounded px-3 py-1.5 {{ request()->routeIs('admin.kategori.*') ? 'bg-brand-dark' : 'hover:bg-white/10' }}">Kategori</a>
                <a href="{{ route('admin.merek.index') }}" class="rounded px-3 py-1.5 {{ request()->routeIs('admin.merek.*') ? 'bg-brand-dark' : 'hover:bg-white/10' }}">Merek</a>
                @if ($pegawai->bisaKelolaPegawai())
                    <a href="{{ route('admin.pegawai.index') }}" class="rounded px-3 py-1.5 {{ request()->routeIs('admin.pegawai.*') ? 'bg-brand-dark' : 'hover:bg-white/10' }}">Pegawai</a>
                @endif
                @if ($pegawai->bisaKelolaPelanggan())
                    <a href="{{ route('admin.pelanggan.index') }}" class="rounded px-3 py-1.5 {{ request()->routeIs('admin.pelanggan.*') ? 'bg-brand-dark' : 'hover:bg-white/10' }}">Pelanggan</a>
                @endif
                @if ($adaAksesTransaksi)
                    <a href="{{ route('admin.transaksi.index') }}" class="rounded px-3 py-1.5 {{ request()->routeIs('admin.transaksi.*') ? 'bg-brand-dark' : 'hover:bg-white/10' }}">
                        Transaksi
                        @if ($pesananMenunggu > 0)
                            <span class="ml-1 rounded-full bg-aksen px-1.5 py-0.5 text-[11px] font-semibold text-white" title="{{ $pesananMenunggu }} pesanan belum dibayar">{{ $pesananMenunggu }}</span>
                        @endif
                    </a>
                @endif
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
        @if ($pesanSukses)
            <div class="mb-4 rounded-lg border border-green-200 bg-brand-soft px-4 py-3 text-sm text-brand-dark" role="status">{{ $pesanSukses }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-brand text-white/90 text-xs">
        <div class="mx-auto max-w-7xl px-4 py-4">Gadgetkuori · Sistem Informasi Toko Internal</div>
    </footer>
</body>

</html>