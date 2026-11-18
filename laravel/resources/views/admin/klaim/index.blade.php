@extends('layouts.admin')
@section('title', 'Klaim Garansi')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Klaim Garansi</h1>
    <p class="text-sm text-slate-500">Beranda › Klaim Garansi</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="bg-brand px-4 py-3 text-white"><h2 class="font-display font-bold">Daftar Klaim</h2></div>

    <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
        <div class="min-w-[12rem] flex-1">
            <label for="q" class="mb-1 block text-xs font-semibold">Cari klaim</label>
            <input id="q" name="q" value="{{ request('q') }}" placeholder="Nomor garansi atau nama pelanggan…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
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
        <a href="{{ route('admin.klaim.index') }}" class="px-2 py-2 text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[48rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">No.</th>
                    <th class="px-4 py-2.5">Tanggal</th>
                    <th class="px-4 py-2.5">Nomor garansi</th>
                    <th class="px-4 py-2.5">Pelanggan</th>
                    <th class="px-4 py-2.5">Produk</th>
                    <th class="px-4 py-2.5">Status</th>
                    <th class="px-4 py-2.5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($klaim as $k)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold">#{{ $k->id_klaim }}</td>
                        <td class="px-4 py-3">{{ $k->tanggal_klaim->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $k->garansi->nomor_garansi }}</td>
                        <td class="px-4 py-3">{{ $k->garansi->detail->transaksi->pelanggan->nama ?? '–' }}</td>
                        <td class="px-4 py-3">{{ $k->garansi->detail->produk->nama_produk ?? 'Produk dihapus' }}</td>
                        <td class="px-4 py-3">@include('garansi._status-klaim', ['klaim' => $k])</td>
                        <td class="px-4 py-3"><a href="{{ route('admin.klaim.show', $k) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">Belum ada klaim yang cocok. Ubah kata kunci atau reset filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($klaim->hasPages())
        <div class="border-t border-slate-200 p-4">{{ $klaim->links() }}</div>
    @endif
</section>
@endsection