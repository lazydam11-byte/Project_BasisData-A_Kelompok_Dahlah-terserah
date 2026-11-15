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
                @endif
            </div>
        </div>
    </header>

    <main class="flex-1 mx-auto w-full max-w-7xl px-4 py-6">
        @yield('content')
    </main>

    <footer class="bg-brand text-white/90 text-xs">
        <div class="mx-auto max-w-7xl px-4 py-4 flex flex-wrap justify-between gap-2">
            <span>Katalog daring, pembelian langsung di toko.</span>
            <span>© 2026 Gadgetkuori</span>
        </div>
    </footer>
</body>
</html>