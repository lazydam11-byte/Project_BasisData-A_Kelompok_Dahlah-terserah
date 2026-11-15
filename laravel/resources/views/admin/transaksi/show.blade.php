@extends('layouts.admin')
@section('title', 'Transaksi #' . $transaksi->id_transaksi)

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Transaksi #{{ $transaksi->id_transaksi }}</h1>
    <p class="text-sm text-slate-500">Beranda › Transaksi › #{{ $transaksi->id_transaksi }}</p>
</div>

<div class="grid gap-4 lg:grid-cols-3">
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
        <div class="bg-brand px-4 py-3 text-white"><h2 class="font-display font-bold">Isi pesanan</h2></div>
        @include('transaksi._detail')
    </section>

    <aside class="h-fit rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="mb-3 font-display font-bold">Informasi</h2>
        <dl class="divide-y divide-slate-100 text-sm">
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Status</dt><dd>@include('transaksi._status', ['transaksi' => $transaksi])</dd></div>
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Tanggal</dt><dd>{{ $transaksi->tanggal->format('d/m/Y H:i') }}</dd></div>
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Metode</dt><dd>{{ $transaksi->metode_pembayaran }}</dd></div>
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Pelanggan</dt><dd class="text-right">{{ $transaksi->pelanggan->nama ?? '–' }}</dd></div>
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Telepon</dt><dd>{{ $transaksi->pelanggan->no_telepon ?? '–' }}</dd></div>
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Diproses oleh</dt><dd>{{ $transaksi->pegawai->nama ?? 'Belum ada' }}</dd></div>
        </dl>

        @if ($transaksi->metode_pembayaran !== 'Bayar di Toko')
            <div class="mt-4 border-t border-slate-100 pt-4">
                <h3 class="mb-2 text-xs font-semibold text-slate-600">Bukti pembayaran</h3>
                @if ($transaksi->bukti_pembayaran)
                    <a href="{{ $transaksi->bukti_url }}" target="_blank" rel="noopener">
                        <img src="{{ $transaksi->bukti_url }}" alt="Bukti pembayaran pesanan {{ $transaksi->id_transaksi }}" class="max-h-64 w-full rounded-lg border border-slate-200 object-contain">
                    </a>
                    <p class="mt-1 text-xs text-slate-500">Cocokkan nominal Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }} dengan mutasi sebelum konfirmasi lunas.</p>
                @else
                    <p class="text-sm text-slate-500">Pelanggan belum mengunggah bukti.</p>
                @endif
            </div>
        @endif

        @if ($transaksi->bisaDiproses())
            <div class="mt-4 flex flex-col gap-2">
                <form method="POST" action="{{ route('admin.transaksi.update', $transaksi) }}" onsubmit="return confirm('Konfirmasi pesanan ini sudah dibayar lunas?')">
                    @csrf @method('PUT')
                    <input type="hidden" name="aksi" value="lunas">
                    <button class="w-full rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Konfirmasi lunas</button>
                </form>
                <form method="POST" action="{{ route('admin.transaksi.update', $transaksi) }}" onsubmit="return confirm('Batalkan pesanan ini? Stok akan dikembalikan.')">
                    @csrf @method('PUT')
                    <input type="hidden" name="aksi" value="batal">
                    <button class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Batalkan pesanan</button>
                </form>
            </div>
        @endif

        <a href="{{ route('admin.transaksi.index') }}" class="mt-4 block text-sm text-slate-500 hover:underline">Kembali ke daftar</a>
    </aside>
</div>
@endsection