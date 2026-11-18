@extends('layouts.admin')
@section('title', 'Transaksi')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Transaksi Penjualan</h1>
    <p class="text-sm text-slate-500">Beranda › Transaksi</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="bg-brand px-4 py-3 text-white"><h2 class="font-display font-bold">Daftar Pesanan</h2></div>

    <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
        <div class="min-w-[12rem] flex-1">
            <label for="q" class="mb-1 block text-xs font-semibold">Cari pesanan</label>
            <input id="q" name="q" value="{{ request('q') }}" placeholder="No. pesanan atau nama pelanggan…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <div>
            <label for="status" class="mb-1 block text-xs font-semibold">Status</label>
            <select id="status" name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                <option value="">Semua status</option>
                @foreach ($daftarStatus as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Cari</button>
        <a href="{{ route('admin.transaksi.index') }}" class="px-2 py-2 text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[44rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">No.</th>
                    <th class="px-4 py-2.5">Tanggal</th>
                    <th class="px-4 py-2.5">Pelanggan</th>
                    <th class="px-4 py-2.5">Metode</th>
                    <th class="px-4 py-2.5">Total (Rp)</th>
                    <th class="px-4 py-2.5">Status</th>
                    <th class="px-4 py-2.5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($transaksi as $t)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold">#{{ $t->id_transaksi }}</td>
                        <td class="px-4 py-3">{{ $t->tanggal->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $t->pelanggan->nama ?? '–' }}</td>
                        <td class="px-4 py-3">{{ $t->metode_pembayaran }}</td>
                        <td class="px-4 py-3">{{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">@include('transaksi._status', ['transaksi' => $t])</td>
                        <td class="px-4 py-3"><a href="{{ route('admin.transaksi.show', $t) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">Belum ada transaksi yang cocok. Ubah kata kunci atau reset filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transaksi->hasPages())
        <div class="border-t border-slate-200 p-4">{{ $transaksi->links() }}</div>
    @endif
</section>
@endsection