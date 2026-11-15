@extends('layouts.katalog')
@section('title', 'Beranda')

@section('content')
@php
    $pelanggan = auth('pelanggan')->user(); // null jika belum login
    $namaDepan = $pelanggan ? \Illuminate\Support\Str::before($pelanggan->nama, ' ') : null;
@endphp

{{-- Banner selamat datang --}}
<section class="mb-6 overflow-hidden rounded-xl bg-brand text-white">
    <div class="grid gap-6 p-6 md:grid-cols-5 md:p-8">
        <div class="md:col-span-3">
            <h1 class="font-display text-3xl font-bold">
                @if ($namaDepan)
                    Halo, {{ $namaDepan }}. Selamat datang kembali.
                @else
                    Selamat datang di Gadgetkuori.com
                @endif
            </h1>
            <p class="mt-2 max-w-prose text-sm opacity-90">Cek harga dan stok gadget sebelum datang ke toko. Pembelian dilayani langsung di toko.</p>

            <form method="GET" action="{{ route('katalog.index') }}" class="mt-5 flex max-w-lg gap-2">
                <label for="q" class="sr-only">Cari produk</label>
                <input id="q" name="q" placeholder="Cari produk, misalnya Galaxy S24…" class="w-full rounded-lg border-0 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-white">
                <button class="rounded-lg bg-aksen px-4 py-2.5 text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-white">Cari</button>
            </form>
        </div>

        <dl class="grid content-center gap-3 rounded-lg bg-white/10 p-4 text-sm md:col-span-2">
            @if ($pelanggan)
                <div>
                    <dt class="text-xs opacity-80">Akun Anda</dt>
                    <dd class="font-semibold">{{ $pelanggan->nama }}</dd>
                </div>
                <div>
                    <dt class="text-xs opacity-80">Kontak</dt>
                    <dd>{{ $pelanggan->no_telepon ?: 'Belum diisi' }}@if ($pelanggan->email) · {{ $pelanggan->email }}@endif</dd>
                </div>
            @else
                <div>
                    <dt class="text-xs opacity-80">Produk tersedia</dt>
                    <dd class="font-display text-2xl font-bold">{{ $statistik['produk_tersedia'] }}</dd>
                </div>
                <div>
                    <dt class="text-xs opacity-80">Kategori dan merek</dt>
                    <dd>{{ $statistik['kategori'] }} kategori dari {{ $statistik['merek'] }} merek</dd>
                </div>
            @endif
        </dl>
    </div>
</section>

{{-- Fasilitas (sesuaikan teksnya dengan layanan toko yang sebenarnya) --}}
<section class="mb-8 rounded-xl border border-slate-200 bg-white shadow-sm" aria-labelledby="judul-fasilitas">
    <h2 id="judul-fasilitas" class="border-b border-slate-200 px-4 py-3 font-display font-bold">Fasilitas untuk Anda</h2>
    <div class="grid divide-y divide-slate-200 sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x">
        <div class="p-4">
            <h3 class="font-semibold">Garansi produk</h3>
            <p class="mt-1 text-sm text-slate-600">Setiap produk dilengkapi garansi. Masa garansi tertera di kartu produk.</p>
        </div>
        <div class="p-4">
            <h3 class="font-semibold">Cek stok online</h3>
            <p class="mt-1 text-sm text-slate-600">Lihat ketersediaan unit sebelum ke toko, jadi tidak datang percuma.</p>
        </div>
        <div class="p-4">
            <h3 class="font-semibold">Konsultasi dengan kasir</h3>
            <p class="mt-1 text-sm text-slate-600">Bingung memilih? Tanyakan langsung ke kasir kami di toko.</p>
        </div>
        <div class="p-4">
            <h3 class="font-semibold">Servis oleh teknisi</h3>
            <p class="mt-1 text-sm text-slate-600">Tim teknisi siap membantu pengecekan dan perbaikan perangkat.</p>
        </div>
    </div>
</section>

{{-- Produk terbaru --}}
<section aria-labelledby="judul-terbaru" class="mb-8">
    <div class="mb-3 flex items-end justify-between gap-3">
        <h2 id="judul-terbaru" class="font-display text-xl font-bold">Baru masuk</h2>
        <a href="{{ route('katalog.index') }}" class="text-sm font-semibold text-brand hover:underline">Lihat semua produk</a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($produkBaru as $p)
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                @if ($p->foto_url)
                    <img src="{{ $p->foto_url }}" alt="{{ $p->nama_produk }}" class="h-40 w-full object-cover">
                @else
                    <div class="flex h-40 items-center justify-center bg-slate-100 text-sm text-slate-400">Belum ada foto</div>
                @endif
                <div class="p-4">
                    @if ($p->stok <= $batasStok)
                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">Stok terbatas · {{ $p->stok }} unit</span>
                    @else
                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800">Stok tersedia · {{ $p->stok }} unit</span>
                    @endif
                    <h3 class="mt-2 font-display text-lg font-bold leading-snug">{{ $p->nama_produk }}</h3>
                    <p class="mt-1 text-xs text-slate-500">{{ $p->merek->nama_merek }} · {{ $p->kategori->nama_kategori }}<br>Garansi: {{ $p->text_garansi }}</p>
                    <p class="mt-2 font-display text-lg font-bold text-brand">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                </div>
            </article>
        @empty
            <p class="col-span-full rounded-xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">Belum ada produk yang tersedia. Silakan cek lagi nanti.</p>
        @endforelse
    </div>
</section>

{{-- Jelajah per kategori --}}
@if ($daftarKategori->isNotEmpty())
    <section aria-labelledby="judul-kategori" class="mb-8">
        <h2 id="judul-kategori" class="mb-3 font-display text-xl font-bold">Cari berdasarkan kategori</h2>
        <div class="flex flex-wrap gap-2">
            @foreach ($daftarKategori as $k)
                <a href="{{ route('katalog.index', ['id_kategori' => $k->id_kategori]) }}" class="rounded-full border border-slate-300 bg-white px-4 py-1.5 text-sm hover:border-brand hover:text-brand focus:outline-none focus:ring-2 focus:ring-brand">{{ $k->nama_kategori }}</a>
            @endforeach
        </div>
    </section>
@endif
@endsection