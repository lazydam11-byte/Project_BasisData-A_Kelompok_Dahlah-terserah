@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
@php
    $bisaKelola = auth('pegawai')->user()->bisaKelolaProduk();
    $perhatian  = $ringkasan['stok_habis'] + $ringkasan['stok_menipis'];
    $maxStok    = max(1, (int) $stokPerKategori->max('total_stok'));
@endphp

<div class="mb-4 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h1 class="font-display text-2xl font-bold">Dashboard</h1>
        <p class="text-sm text-slate-500">Ringkasan stok dan produk hari ini, {{ now()->translatedFormat('d F Y') }}</p>
    </div>
    @if ($bisaKelola)
        <a href="{{ route('admin.produk.create') }}" class="rounded bg-aksen px-3 py-1.5 text-sm font-semibold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brand">+ Tambah Produk</a>
    @endif
</div>

{{-- Banner peringatan: hanya muncul jika ada stok bermasalah --}}
@if ($perhatian > 0)
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900" role="alert">
        <p>
            <span class="font-semibold">{{ $perhatian }} produk perlu perhatian.</span>
            {{ $ringkasan['stok_habis'] }} stok habis, {{ $ringkasan['stok_menipis'] }} hampir habis (sisa {{ $batas }} unit atau kurang).
        </p>
        <a href="{{ route('admin.produk.index') }}" class="font-semibold underline hover:no-underline">Lihat data produk</a>
    </div>
@endif

<div class="grid gap-4 lg:grid-cols-3">

    {{-- Kolom kiri: daftar restock (fokus utama dashboard) --}}
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between bg-brand px-4 py-3 text-white">
            <h2 class="font-display font-bold">Perlu restock</h2>
            <span class="text-xs opacity-80">Batas stok menipis: {{ $batas }} unit</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[34rem] text-left text-sm">
                <thead class="bg-slate-50 text-xs text-slate-600">
                    <tr>
                        <th class="px-4 py-2.5">Produk</th>
                        <th class="px-4 py-2.5">Kategori</th>
                        <th class="px-4 py-2.5">Stok</th>
                        @if ($bisaKelola) <th class="px-4 py-2.5">Aksi</th> @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($perluRestock as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($p->foto_url)
                                        <img src="{{ $p->foto_url }}" alt="" class="h-10 w-10 rounded object-cover">
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded bg-slate-100 text-[10px] text-slate-400">–</div>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $p->nama_produk }}</p>
                                        <p class="text-xs text-slate-500">{{ $p->merek->nama_merek }} · {{ $p->kode }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $p->kategori->nama_kategori }}</td>
                            <td class="px-4 py-3">
                                @if ($p->stok == 0)
                                    <span class="rounded bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">Habis</span>
                                @else
                                    <span class="rounded bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">Sisa {{ $p->stok }}</span>
                                @endif
                            </td>
                            @if ($bisaKelola)
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.produk.edit', $p) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Ubah stok</a>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">Semua produk stoknya aman. Belum ada yang perlu di-restock.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Kolom kanan: ringkasan angka --}}
    <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="mb-3 font-display font-bold">Ringkasan</h2>
        <dl class="divide-y divide-slate-100 text-sm">
            <div class="flex items-center justify-between py-2.5">
                <dt class="text-slate-600">Jenis produk</dt>
                <dd class="font-display text-lg font-bold">{{ number_format($ringkasan['total_produk'], 0, ',', '.') }}</dd>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <dt class="text-slate-600">Total unit di gudang</dt>
                <dd class="font-display text-lg font-bold">{{ number_format($ringkasan['total_unit'], 0, ',', '.') }}</dd>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <dt class="text-slate-600">Nilai persediaan</dt>
                <dd class="font-display text-lg font-bold">Rp {{ number_format($ringkasan['nilai_persediaan'], 0, ',', '.') }}</dd>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <dt class="text-slate-600">Stok habis</dt>
                <dd class="font-display text-lg font-bold {{ $ringkasan['stok_habis'] ? 'text-red-600' : '' }}">{{ $ringkasan['stok_habis'] }}</dd>
            </div>
            <div class="flex items-center justify-between py-2.5">
                <dt class="text-slate-600">Stok menipis</dt>
                <dd class="font-display text-lg font-bold {{ $ringkasan['stok_menipis'] ? 'text-amber-600' : '' }}">{{ $ringkasan['stok_menipis'] }}</dd>
            </div>
            @if (!is_null($ringkasan['total_pelanggan']))
                <div class="flex items-center justify-between py-2.5">
                    <dt class="text-slate-600">Pelanggan terdaftar</dt>
                    <dd class="font-display text-lg font-bold">{{ number_format($ringkasan['total_pelanggan'], 0, ',', '.') }}</dd>
                </div>
            @endif
        </dl>
    </section>

    {{-- Stok per kategori --}}
    <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="mb-3 font-display font-bold">Stok per kategori</h2>
        @forelse ($stokPerKategori as $row)
            <div class="mb-3 last:mb-0">
                <div class="mb-1 flex justify-between text-xs">
                    <span class="font-semibold">{{ $row->nama }}</span>
                    <span class="text-slate-500">{{ number_format($row->total_stok, 0, ',', '.') }} unit · {{ $row->jumlah_produk }} produk</span>
                </div>
                <div class="h-2 overflow-hidden rounded bg-slate-100" role="img" aria-label="{{ $row->nama }}: {{ $row->total_stok }} unit">
                    <div class="h-full rounded bg-brand" style="width: {{ round($row->total_stok / $maxStok * 100) }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-500">Belum ada produk.</p>
        @endforelse
    </section>

    {{-- Produk terbaru --}}
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <h2 class="font-display font-bold">Baru masuk</h2>
            <a href="{{ route('admin.produk.index') }}" class="text-sm text-slate-500 hover:underline">Semua produk</a>
        </div>
        <ul class="divide-y divide-slate-100 text-sm">
            @forelse ($produkTerbaru as $p)
                <li class="flex items-center justify-between gap-3 px-4 py-3">
                    <div class="min-w-0">
                        <p class="truncate font-semibold">{{ $p->nama_produk }}</p>
                        <p class="text-xs text-slate-500">{{ $p->kategori->nama_kategori }} · {{ $p->merek->nama_merek }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p>Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-500">{{ $p->tanggal_masuk->format('d/m/Y') }} · stok {{ $p->stok }}</p>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-slate-500">Belum ada produk masuk.</li>
            @endforelse
        </ul>
    </section>

</div>
@endsection