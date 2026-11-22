@extends('layouts.katalog')
@section('title', 'Beranda')

@section('content')
@php
    $pelanggan = auth('pelanggan')->user();
    $namaDepan = $pelanggan
        ? \Illuminate\Support\Str::before($pelanggan->nama, ' ')
        : null;
@endphp

{{-- HERO BANNER --}}
<section class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-blue-900 to-brand text-white shadow-lg">
    <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
    <div class="absolute -bottom-20 right-1/3 h-56 w-56 rounded-full bg-blue-400/20 blur-3xl"></div>

    <div class="relative grid items-center gap-8 p-6 md:grid-cols-5 md:p-10">

        <div class="md:col-span-3">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100">
                <i class="fa-solid fa-bolt text-yellow-300"></i>
                Your Gadget, Your Choice
            </span>

            <h1 class="mt-5 font-display text-3xl font-extrabold leading-tight md:text-5xl">
                @if ($namaDepan)
                    Halo, {{ $namaDepan }}.
                    <span class="text-blue-300">Selamat datang kembali!</span>
                @else
                    Temukan Gadget
                    <span class="text-blue-300">Impianmu!</span>
                @endif
            </h1>

            <p class="mt-4 max-w-lg text-sm leading-relaxed text-blue-100 md:text-base">
                Temukan gadget favoritmu, cek harga dan ketersediaan stok,
                lalu lakukan pembelian secara langsung melalui website kami
                dengan mudah dan praktis.
            </p>

            <form method="GET" action="{{ route('katalog.index') }}"
                class="mt-6 flex max-w-lg gap-2 rounded-xl bg-white p-1.5 shadow-lg">

                <label for="q" class="sr-only">Cari produk</label>

                <div class="flex flex-1 items-center gap-2 pl-3">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    <input id="q" name="q"
                        placeholder="Cari gadget favoritmu..."
                        class="w-full border-0 bg-transparent py-2 text-sm text-slate-800 outline-none focus:ring-0">
                </div>

                <button class="rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                    Cari
                </button>
            </form>

            <a href="{{ route('katalog.index') }}"
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-white transition hover:text-blue-300">
                Belanja sekarang
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        {{-- INFORMASI AKUN / STATISTIK --}}
        <div class="relative md:col-span-2">
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 shadow-xl backdrop-blur-md">

                @if ($pelanggan)
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20">
                            <i class="fa-solid fa-user text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-blue-200">Selamat datang</p>
                            <p class="font-bold">{{ $pelanggan->nama }}</p>
                        </div>
                    </div>

                    <div class="border-t border-white/20 pt-4">
                        <p class="text-xs text-blue-200">Informasi kontak</p>
                        <p class="mt-1 text-sm">
                            <i class="fa-solid fa-phone mr-2"></i>
                            {{ $pelanggan->no_telepon ?: 'Belum diisi' }}
                        </p>

                        @if ($pelanggan->email)
                            <p class="mt-2 break-all text-sm">
                                <i class="fa-solid fa-envelope mr-2"></i>
                                {{ $pelanggan->email }}
                            </p>
                        @endif
                    </div>
                @else
                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20">
                            <i class="fa-solid fa-store text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-blue-200">Informasi toko</p>
                            <p class="font-bold">Gadgetkuori.com</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white/10 p-4">
                            <i class="fa-solid fa-box-open mb-2 text-xl text-blue-300"></i>
                            <p class="text-2xl font-bold">
                                {{ $statistik['produk_tersedia'] }}
                            </p>
                            <p class="text-xs text-blue-200">Produk tersedia</p>
                        </div>

                        <div class="rounded-xl bg-white/10 p-4">
                            <i class="fa-solid fa-layer-group mb-2 text-xl text-blue-300"></i>
                            <p class="text-2xl font-bold">
                                {{ $statistik['kategori'] }}
                            </p>
                            <p class="text-xs text-blue-200">Kategori produk</p>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-blue-200">
                        Pilihan gadget dari berbagai merek tersedia untukmu.
                    </p>
                @endif
            </div>
        </div>
    </div>
</section>


{{-- FASILITAS --}}
<section class="mb-10" aria-labelledby="judul-fasilitas">

    <div class="mb-4">
        <h2 id="judul-fasilitas" class="font-display text-xl font-bold text-slate-800">
            Mengapa Belanja di Gadgetkuori?
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Nikmati kemudahan dan kenyamanan berbelanja gadget.
        </p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800">Garansi Produk</h3>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                Garansi terbit otomatis setelah pembayaran dikonfirmasi lunas, sesuai masa garansi tiap produk.
            </p>
        </div>

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-300 hover:shadow-lg">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600 transition group-hover:bg-green-600 group-hover:text-white">
                <i class="fa-solid fa-cart-shopping text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800">Belanja Online</h3>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                Pilih gadget favorit dan lakukan pemesanan langsung melalui website dengan mudah.
            </p>
        </div>

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-purple-300 hover:shadow-lg">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600 transition group-hover:bg-purple-600 group-hover:text-white">
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800">Pesanan Terpantau</h3>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                Pantau status pembayaran dan pengiriman (lengkap dengan nomor resi), unggah bukti transfer, dan lihat riwayat belanja.
            </p>
        </div>

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-orange-300 hover:shadow-lg">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-orange-600 transition group-hover:bg-orange-500 group-hover:text-white">
                <i class="fa-solid fa-screwdriver-wrench text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800">Klaim Garansi Online</h3>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                Ajukan klaim garansi dari akun Anda dan pantau penanganannya oleh teknisi.
            </p>
        </div>

    </div>
</section>


{{-- PRODUK TERBARU --}}
<section aria-labelledby="judul-terbaru" class="mb-10">

    <div class="mb-5 flex items-end justify-between gap-3">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand">
                Koleksi terbaru
            </span>
            <h2 id="judul-terbaru" class="mt-1 font-display text-2xl font-bold text-slate-800">
                Baru Masuk
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Temukan produk gadget terbaru pilihan kami.
            </p>
        </div>

        <a href="{{ route('katalog.index') }}"
            class="whitespace-nowrap text-sm font-semibold text-brand transition hover:underline">
            Lihat semua
            <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

        @forelse ($produkBaru as $p)

            <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                <div class="relative overflow-hidden bg-slate-50">

                    @if ($p->foto_url)
                        <img src="{{ $p->foto_url }}"
                            alt="{{ $p->nama_produk }}"
                            class="h-48 w-full object-contain p-4 transition duration-500 group-hover:scale-105">
                    @else
                        <div class="flex h-48 items-center justify-center text-slate-400">
                            <div class="text-center">
                                <i class="fa-solid fa-image text-4xl"></i>
                                <p class="mt-2 text-xs">Belum ada foto</p>
                            </div>
                        </div>
                    @endif

                    <span class="absolute left-3 top-3 rounded-full bg-brand px-3 py-1 text-xs font-semibold text-white shadow">
                        Baru
                    </span>
                </div>

                <div class="p-4">

                    @if ($p->stok <= $batasStok)
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            Stok terbatas · {{ $p->stok }} unit
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                            <i class="fa-solid fa-circle-check"></i>
                            Stok tersedia · {{ $p->stok }} unit
                        </span>
                    @endif

                    <h3 class="mt-3 line-clamp-2 min-h-12 font-display font-bold text-slate-800">
                        {{ $p->nama_produk }}
                    </h3>

                    <p class="mt-2 text-xs text-slate-500">
                        {{ $p->merek->nama_merek }}
                        <span class="mx-1">·</span>
                        {{ $p->kategori->nama_kategori }}
                    </p>

                    <div class="mt-2 flex items-center gap-1 text-xs text-slate-500">
                        <i class="fa-solid fa-shield-halved text-brand"></i>
                        Garansi: {{ $p->text_garansi }}
                    </div>

                    <div class="mt-4 border-t border-slate-100 pt-3">
                        <p class="text-xs text-slate-400">Harga produk</p>
                        <p class="mt-1 font-display text-xl font-extrabold text-brand">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </p>
                    </div>

                </div>
            </article>

        @empty

            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                <i class="fa-solid fa-box-open text-4xl text-slate-300"></i>
                <p class="mt-3 font-semibold text-slate-600">
                    Belum ada produk tersedia
                </p>
                <p class="mt-1 text-sm text-slate-400">
                    Silakan cek kembali nanti.
                </p>
            </div>

        @endforelse

    </div>
</section>


{{-- KATEGORI --}}
@if ($daftarKategori->isNotEmpty())

<section aria-labelledby="judul-kategori" class="mb-10">

    <div class="mb-5">
        <span class="text-xs font-bold uppercase tracking-wider text-brand">
            Jelajahi koleksi
        </span>

        <h2 id="judul-kategori" class="mt-1 font-display text-2xl font-bold text-slate-800">
            Cari Berdasarkan Kategori
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Temukan gadget sesuai kebutuhanmu.
        </p>
    </div>

    <div class="flex flex-wrap gap-3">

        @foreach ($daftarKategori as $k)

            <a href="{{ route('katalog.index', ['id_kategori' => $k->id_kategori]) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:border-brand hover:bg-brand hover:text-white hover:shadow-md">

                <i class="fa-solid fa-layer-group"></i>
                {{ $k->nama_kategori }}
                <i class="fa-solid fa-arrow-right text-xs"></i>

            </a>

        @endforeach

    </div>

</section>

@endif

@endsection