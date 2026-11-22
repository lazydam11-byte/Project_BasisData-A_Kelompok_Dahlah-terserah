@php $pelanggan = auth('pelanggan')->user(); @endphp
<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts._head')
    <title>@yield('title', 'Katalog') · Gadgetkuori.com</title>
</head>

<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800 font-sans">
    <header class="bg-brand text-white">
        <div class="mx-auto max-w-7xl px-4 py-3 flex flex-wrap items-center gap-x-6 gap-y-2">
            <a href="{{ route('beranda') }}" class="font-display font-bold text-lg">Gadgetkuori.com</a>

            <nav aria-label="Menu utama" class="flex items-center gap-1 text-sm">
                <a href="{{ route('beranda') }}"
                   @if (request()->routeIs('beranda')) aria-current="page" @endif
                   class="rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-white {{ request()->routeIs('beranda') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">Beranda</a>
                <a href="{{ route('katalog.index') }}"
                   @if (request()->routeIs('katalog.*')) aria-current="page" @endif
                   class="rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-white {{ request()->routeIs('katalog.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">Produk</a>
                <a href="{{ route('tentang') }}"
                   @if (request()->routeIs('tentang')) aria-current="page" @endif
                   class="rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-white {{ request()->routeIs('tentang') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">Tentang</a>
                <a class="rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-white {{ request()->routeIs('keranjang.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}" href="{{ route('keranjang.index') }}">Keranjang ({{ array_sum(session('keranjang', [])) }}) </a>
                @if ($pelanggan) 
                    <a class="rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-white {{ request()->routeIs('pesanan.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}" href="{{ route('pesanan.index') }}">Pesanan saya</a> 
                @endif
                @if ($pelanggan) <a class="rounded px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-white {{ request()->routeIs('garansi.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}" href="{{ route('garansi.index') }}">Garansi</a> 
                @endif
            </nav>

            <div class="ml-auto flex items-center gap-3 text-sm">
                @if ($pelanggan)
                <span class="font-semibold">{{ $pelanggan->nama }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded border border-white/60 px-3 py-1.5 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">Keluar</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="rounded border border-white/60 px-3 py-1.5 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">Masuk</a>
                <a href="{{ route('daftar') }}" class="rounded bg-aksen px-3 py-1.5 font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-white">Daftar</a>
                @endif
            </div>
        </div>
    </header>
    <main class="flex-1 mx-auto w-full max-w-7xl px-4 py-6">
        @php $pesanSukses = session('success') ?? session('sukses'); @endphp
        @if ($pesanSukses)
            <div class="mb-4 rounded-lg border border-green-200 bg-brand-soft px-4 py-3 text-sm text-brand-dark" role="status">{{ $pesanSukses }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
    <footer class="bg-brand text-white/90 text-xs">
        <div class="mx-auto max-w-7xl px-4 py-4 flex flex-wrap justify-between gap-2">
            <span>Gadgetkuori.com Toko Online Gadget Terpercaya</span>
            <span>© 2026 Gadgetkuori</span>
        </div>
    </footer>
</body>
</html>