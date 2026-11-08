@extends('layouts.katalog')
@section('title', 'Semua Produk')
@section('content')
@php $pelanggan = auth('pelanggan')->user(); @endphp
<div class="mb-5">
    @if ($pelanggan)
        <p class="text-sm text-slate-500">Halo, {{ $pelanggan->nama }}. Selamat berbelanja!</p>
    @endif
    <h1 class="font-display text-3xl font-bold">Semua Produk</h1>
    <p class="text-sm text-slate-500">Cek harga dan stok sebelum datang ke toko. Pembelian dilayani langsung di toko.</p>
</div>

<form method="GET" class="mb-6 flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4">
    <div class="min-w-[12rem] flex-1">
        <label for="q" class="mb-1 block text-xs font-semibold">Cari produk</label>
        <input id="q" name="q" value="{{ request('q') }}" placeholder="Cari nama produk, misalnya Galaxy S24…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
    </div>
    <div>
        <label for="id_kategori" class="mb-1 block text-xs font-semibold">Kategori</label>
        <select id="id_kategori" name="id_kategori" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
            <option value="">Semua kategori</option>
            @foreach ($daftarKategori as $k)
                <option value="{{ $k->id_kategori }}" @selected(request('id_kategori') == $k->id_kategori)>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="id_merek" class="mb-1 block text-xs font-semibold">Merek</label>
        <select id="id_merek" name="id_merek" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
            <option value="">Semua merek</option>
            @foreach ($daftarMerek as $m)
                <option value="{{ $m->id_merek }}" @selected(request('id_merek') == $m->id_merek)>{{ $m->nama_merek }}</option>
            @endforeach
        </select>
    </div>
    <label class="flex items-center gap-2 py-2 text-sm">
        <input type="checkbox" name="stok_tersedia" value="1" @checked(request()->boolean('stok_tersedia')) class="rounded border-slate-300 text-brand focus:ring-brand">
        Hanya stok tersedia
    </label>
    <button class="rounded-lg bg-brand px-5 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Cari Produk</button>
</form>

<p class="mb-3 text-sm text-slate-500">Menampilkan {{ $produk->count() }} dari {{ $produk->total() }} produk</p>

@if ($produk->isEmpty())
    <div class="rounded-xl border border-slate-200 bg-white p-10 text-center">
        <h2 class="font-display text-xl font-bold">Produk tidak ditemukan</h2>
        <p class="mt-1 text-sm text-slate-500">Coba kata kunci lain atau hapus filter kategori dan merek.</p>
        <a href="{{ route('katalog') }}" class="mt-4 inline-block rounded-lg border border-brand px-4 py-2 text-sm font-semibold text-brand hover:bg-brand-soft">Hapus Pencarian & Filter</a>
    </div>
@else
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($produk as $p)
            <article class="flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white">
                @if ($p->foto_url)
                    <img src="{{ $p->foto_url }}" alt="{{ $p->nama_produk }}" class="h-44 w-full object-cover">
                @else
                    <div class="flex h-44 items-center justify-center bg-slate-100 text-sm text-slate-400">Belum ada foto</div>
                @endif
                <div class="flex flex-1 flex-col p-4">
                    <span class="w-fit rounded-full px-2 py-0.5 text-xs font-semibold {{ $p->status_stok[1] }}">{{ $p->status_stok[0] }}</span>
                    <h2 class="mt-2 font-display text-lg font-bold leading-snug">{{ $p->nama_produk }}</h2>
                    <p class="mt-auto pt-3 text-xs text-slate-500">{{ $p->merek->nama_merek }} · {{ $p->kategori->nama_kategori }}</p>
                    <p class="text-xs text-slate-500">Garansi: {{ $p->text_garansi }}</p>
                    <p class="mt-1 font-display text-xl font-bold text-brand">{{ $p->harga_rupiah }}</p>
                </div>
            </article>
        @endforeach
    </div>

    @if ($produk->hasPages())
        <div class="mt-6">{{ $produk->links() }}</div>
    @endif
@endif

<div class="mt-8 rounded-xl bg-brand-soft px-4 py-3 text-sm text-brand-dark">
    Sudah menemukan pilihan? Datang ke toko. Pegawai kami membantu transaksi, pembayaran, dan garansi.
</div>
@endsection
