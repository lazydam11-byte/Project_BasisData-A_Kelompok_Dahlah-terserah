@extends('layouts.admin')
@section('title', 'Laporan Penjualan')

@section('content')
<style>
    .hanya-cetak { display: none; }
    @media print {
        header, footer, .tidak-dicetak { display: none !important; }
        .hanya-cetak { display: block; }
        body { background: #fff; }
        main { max-width: none; padding: 0; }
    }
</style>

@php
    $rupiah  = fn ($angka) => 'Rp ' . number_format($angka, 0, ',', '.');
    $periode = \Carbon\Carbon::parse($filter['dari'])->format('d/m/Y') . ' – ' . \Carbon\Carbon::parse($filter['sampai'])->format('d/m/Y');
@endphp

<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Laporan Penjualan</h1>
    <p class="text-sm text-slate-500">Beranda › Laporan › Penjualan</p>
    <p class="hanya-cetak mt-1 text-sm">Gadgetkuori · Periode {{ $periode }}{{ $filter['metode'] ? ' · ' . $filter['metode'] : '' }}</p>
</div>

{{-- Filter --}}
<form method="GET" class="tidak-dicetak mb-4 flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <div>
        <label for="dari" class="mb-1 block text-xs font-semibold">Dari tanggal</label>
        <input id="dari" type="date" name="dari" value="{{ $filter['dari'] }}" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
    </div>
    <div>
        <label for="sampai" class="mb-1 block text-xs font-semibold">Sampai tanggal</label>
        <input id="sampai" type="date" name="sampai" value="{{ $filter['sampai'] }}" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
    </div>
    <div>
        <label for="metode" class="mb-1 block text-xs font-semibold">Metode pembayaran</label>
        <select id="metode" name="metode" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
            <option value="">Semua metode</option>
            @foreach ($daftarMetode as $m)
                <option value="{{ $m }}" @selected($filter['metode'] === $m)>{{ $m }}</option>
            @endforeach
        </select>
    </div>
    <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Tampilkan</button>
    <a href="{{ route('admin.laporan.penjualan') }}" class="px-2 py-2 text-sm text-slate-500 hover:underline">Reset</a>

    <div class="ml-auto flex gap-2">
        <button type="button" onclick="window.print()" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Cetak / simpan PDF</button>
        <a href="{{ route('admin.laporan.penjualan.csv', $filter) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Unduh CSV</a>
    </div>
</form>
@error('dari') <p class="tidak-dicetak mb-3 text-sm text-red-600">{{ $message }}</p> @enderror
@error('sampai') <p class="tidak-dicetak mb-3 text-sm text-red-600">{{ $message }}</p> @enderror

<p class="mb-3 text-sm text-slate-500">Periode {{ $periode }}. Hanya transaksi berstatus <span class="font-semibold">Lunas</span> yang dihitung.</p>

{{-- Ringkasan --}}
<section class="mb-4 rounded-xl border border-slate-200 bg-white shadow-sm" aria-label="Ringkasan penjualan">
    <dl class="grid divide-y divide-slate-200 sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x">
        <div class="p-4">
            <dt class="text-xs text-slate-500">Total pendapatan</dt>
            <dd class="font-display text-2xl font-bold">{{ $rupiah($ringkasan['pendapatan']) }}</dd>
        </div>
        <div class="p-4">
            <dt class="text-xs text-slate-500">Jumlah transaksi</dt>
            <dd class="font-display text-2xl font-bold">{{ $ringkasan['jumlah_transaksi'] }}</dd>
        </div>
        <div class="p-4">
            <dt class="text-xs text-slate-500">Unit terjual</dt>
            <dd class="font-display text-2xl font-bold">{{ $ringkasan['unit_terjual'] }}</dd>
        </div>
        <div class="p-4">
            <dt class="text-xs text-slate-500">Rata-rata per transaksi</dt>
            <dd class="font-display text-2xl font-bold">{{ $rupiah($ringkasan['rata_rata']) }}</dd>
        </div>
    </dl>
</section>

<div class="mb-4 grid gap-4 lg:grid-cols-2">
    {{-- Produk terlaris --}}
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <h2 class="border-b border-slate-200 px-4 py-3 font-display font-bold">Produk terlaris</h2>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr><th class="px-4 py-2.5">Produk</th><th class="px-4 py-2.5">Unit</th><th class="px-4 py-2.5 text-right">Omzet (Rp)</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($terlaris as $p)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $p['nama'] }}</td>
                        <td class="px-4 py-3">{{ $p['unit'] }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($p['omzet'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">Belum ada penjualan pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    {{-- Per metode pembayaran --}}
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <h2 class="border-b border-slate-200 px-4 py-3 font-display font-bold">Per metode pembayaran</h2>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr><th class="px-4 py-2.5">Metode</th><th class="px-4 py-2.5">Transaksi</th><th class="px-4 py-2.5 text-right">Total (Rp)</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($perMetode as $metode => $d)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $metode }}</td>
                        <td class="px-4 py-3">{{ $d['jumlah'] }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($d['total'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">Belum ada penjualan pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>

{{-- Rincian transaksi --}}
<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <h2 class="border-b border-slate-200 px-4 py-3 font-display font-bold">Rincian transaksi</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[44rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">No.</th>
                    <th class="px-4 py-2.5">Tanggal</th>
                    <th class="px-4 py-2.5">Pelanggan</th>
                    <th class="px-4 py-2.5">Metode</th>
                    <th class="px-4 py-2.5">Unit</th>
                    <th class="px-4 py-2.5 text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($transaksi as $t)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold">
                            <a href="{{ route('admin.transaksi.show', $t) }}" class="hover:underline">#{{ $t->id_transaksi }}</a>
                        </td>
                        <td class="px-4 py-3">{{ $t->tanggal->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $t->pelanggan->nama ?? '–' }}</td>
                        <td class="px-4 py-3">{{ $t->metode_pembayaran }}</td>
                        <td class="px-4 py-3">{{ $t->detail->sum('jumlah') }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($t->subtotal_produk, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">Belum ada penjualan lunas pada periode ini. Ubah rentang tanggal.</td></tr>
                @endforelse
            </tbody>
            @if ($transaksi->isNotEmpty())
                <tfoot>
                    <tr class="border-t border-slate-200 font-semibold">
                        <td colspan="4" class="px-4 py-3 text-right">Total</td>
                        <td class="px-4 py-3">{{ $ringkasan['unit_terjual'] }}</td>
                        <td class="px-4 py-3 text-right font-display text-lg">{{ number_format($ringkasan['pendapatan'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</section>

<p class="hanya-cetak mt-4 text-xs">Dicetak oleh {{ auth('pegawai')->user()->nama }} pada {{ now()->format('d/m/Y H:i') }}</p>
@endsection