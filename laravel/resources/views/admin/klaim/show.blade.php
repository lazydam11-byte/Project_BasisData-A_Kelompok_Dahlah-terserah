@extends('layouts.admin')
@section('title', 'Klaim #' . $klaim->id_klaim)

@section('content')
@php
    $g         = $klaim->garansi;
    $pelanggan = $g->detail->transaksi->pelanggan ?? null;
@endphp
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Klaim #{{ $klaim->id_klaim }}</h1>
    <p class="text-sm text-slate-500">Beranda › Klaim Garansi › #{{ $klaim->id_klaim }}</p>
</div>

<div class="grid gap-4 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 font-display font-bold">Pengajuan pelanggan</h2>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-xs text-slate-500">Tanggal klaim</dt><dd>{{ $klaim->tanggal_klaim->format('d/m/Y') }}</dd></div>
                <div><dt class="text-xs text-slate-500">Keluhan</dt><dd class="whitespace-pre-line">{{ $klaim->keluhan }}</dd></div>
                <div><dt class="text-xs text-slate-500">Kondisi barang saat klaim</dt><dd class="whitespace-pre-line">{{ $klaim->kondisi_barang }}</dd></div>
            </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 font-display font-bold">Garansi</h2>
            <dl class="divide-y divide-slate-100 text-sm">
                <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Nomor garansi</dt><dd class="font-semibold">{{ $g->nomor_garansi }}</dd></div>
                <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Produk</dt><dd class="text-right">{{ $g->detail->produk->nama_produk ?? 'Produk dihapus' }}</dd></div>
                <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Masa berlaku</dt><dd>{{ $g->tanggal_mulai->format('d/m/Y') }} – {{ $g->tanggal_berakhir->format('d/m/Y') }}</dd></div>
                <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Pelanggan</dt><dd class="text-right">{{ $pelanggan->nama ?? '–' }}</dd></div>
                <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Telepon</dt><dd>{{ $pelanggan->no_telepon ?? '–' }}</dd></div>
            </dl>
            @if ($g->ketentuan_garansi)
                <p class="mt-3 text-xs text-slate-500"><span class="font-semibold">Ketentuan:</span> {{ $g->ketentuan_garansi }}</p>
            @endif
        </section>
    </div>

    <aside class="h-fit rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="font-display font-bold">Penanganan</h2>
            @include('garansi._status-klaim', ['klaim' => $klaim])
        </div>

        <dl class="divide-y divide-slate-100 text-sm">
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Ditangani oleh</dt><dd>{{ $klaim->pegawai->nama ?? 'Belum ada' }}</dd></div>
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Tanggal selesai</dt><dd>{{ $klaim->tanggal_selesai?->format('d/m/Y') ?? '–' }}</dd></div>
        </dl>

        @if ($klaim->keterangan)
            <div class="mt-3">
                <p class="text-xs text-slate-500">Keterangan penanganan</p>
                <p class="whitespace-pre-line text-sm">{{ $klaim->keterangan }}</p>
            </div>
        @endif

        @if ($klaim->masihBerjalan())
            <form method="POST" action="{{ route('admin.klaim.update', $klaim) }}" class="mt-4 border-t border-slate-100 pt-4">
                @csrf @method('PUT')
                <label for="keterangan" class="mb-1 block text-xs font-semibold">Keterangan penanganan</label>
                <textarea id="keterangan" name="keterangan" rows="4" maxlength="1000" placeholder="Hasil pemeriksaan, tindakan, atau alasan penolakan. Wajib diisi untuk Selesai atau Tolak." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">{{ old('keterangan', $klaim->keterangan) }}</textarea>
                @error('keterangan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                <div class="mt-3 flex flex-col gap-2">
                    @if ($klaim->status_klaim === 'Diajukan')
                        <button name="aksi" value="proses" class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Proses klaim</button>
                    @else
                        <button name="aksi" value="selesai" onclick="return confirm('Tandai klaim ini selesai?')" class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Tandai selesai</button>
                    @endif
                    <button name="aksi" value="tolak" onclick="return confirm('Tolak klaim ini?')" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Tolak klaim</button>
                </div>
            </form>
        @endif

        <a href="{{ route('admin.klaim.index') }}" class="mt-4 block text-sm text-slate-500 hover:underline">Kembali ke daftar</a>
    </aside>
</div>
@endsection