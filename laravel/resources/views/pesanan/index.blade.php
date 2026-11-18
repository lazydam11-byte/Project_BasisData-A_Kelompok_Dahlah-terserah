@extends('layouts.katalog')
@section('title', 'Pesanan Saya')

@section('content')
<h1 class="mb-4 font-display text-2xl font-bold">Pesanan saya</h1>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[40rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">No. pesanan</th>
                    <th class="px-4 py-2.5">Tanggal</th>
                    <th class="px-4 py-2.5">Metode</th>
                    <th class="px-4 py-2.5">Total (Rp)</th>
                    <th class="px-4 py-2.5">Status</th>
                    <th class="px-4 py-2.5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pesanan as $t)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold">#{{ $t->id_transaksi }}</td>
                        <td class="px-4 py-3">{{ $t->tanggal->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $t->metode_pembayaran }}</td>
                        <td class="px-4 py-3">{{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">@include('transaksi._status', ['transaksi' => $t])</td>
                        <td class="px-4 py-3"><a href="{{ route('pesanan.show', $t) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Lihat</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">Belum ada pesanan. <a href="{{ route('katalog.index') }}" class="font-semibold text-brand hover:underline">Lihat produk</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($pesanan->hasPages())
        <div class="border-t border-slate-200 p-4">{{ $pesanan->links() }}</div>
    @endif
</section>
@endsection