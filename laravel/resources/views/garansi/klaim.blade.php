@extends('layouts.katalog')
@section('title', 'Ajukan Klaim Garansi')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Ajukan klaim garansi</h1>
    <p class="text-sm text-slate-500">Garansi › {{ $garansi->nomor_garansi }}</p>
</div>

<div class="grid gap-4 lg:grid-cols-3">
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
        <form method="POST" action="{{ route('garansi.klaim.store', $garansi) }}">
            @csrf
            <div class="space-y-4 p-4">
                <div>
                    <label for="keluhan" class="mb-1 block text-xs font-semibold">Keluhan</label>
                    <textarea id="keluhan" name="keluhan" rows="4" required maxlength="1000" placeholder="Jelaskan masalah yang Anda alami, kapan mulai terjadi, dan apa yang sudah dicoba." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">{{ old('keluhan') }}</textarea>
                    @error('keluhan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kondisi_barang" class="mb-1 block text-xs font-semibold">Kondisi barang saat ini</label>
                    <textarea id="kondisi_barang" name="kondisi_barang" rows="3" required maxlength="500" placeholder="Misalnya: layar mulus, ada goresan di bodi, pernah jatuh atau tidak, kelengkapan yang dibawa." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">{{ old('kondisi_barang') }}</textarea>
                    @error('kondisi_barang') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-slate-200 p-4">
                <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Kirim klaim</button>
                <a href="{{ route('garansi.index') }}" class="text-sm text-slate-500 hover:underline">Batal</a>
            </div>
        </form>
    </section>

    <aside class="h-fit rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="mb-3 font-display font-bold">Garansi yang diklaim</h2>
        <dl class="divide-y divide-slate-100 text-sm">
            <div class="py-2"><dt class="text-xs text-slate-500">Produk</dt><dd class="font-semibold">{{ $garansi->detail->produk->nama_produk ?? 'Produk dihapus' }}</dd></div>
            <div class="py-2"><dt class="text-xs text-slate-500">Nomor garansi</dt><dd>{{ $garansi->nomor_garansi }}</dd></div>
            <div class="py-2"><dt class="text-xs text-slate-500">Masa berlaku</dt><dd>{{ $garansi->tanggal_mulai->format('d/m/Y') }} – {{ $garansi->tanggal_berakhir->format('d/m/Y') }} ({{ $garansi->lama_garansi }} bulan)</dd></div>
        </dl>

        @if ($garansi->ketentuan_garansi)
            <h3 class="mb-1 mt-3 text-xs font-semibold text-slate-600">Ketentuan garansi</h3>
            <p class="text-sm text-slate-600">{{ $garansi->ketentuan_garansi }}</p>
        @endif
    </aside>
</div>
@endsection