@extends('layouts.admin')
@section('title', 'Transaksi #' . $transaksi->id_transaksi)

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Transaksi #{{ $transaksi->id_transaksi }}</h1>
    <p class="text-sm text-slate-500">Beranda › Transaksi › #{{ $transaksi->id_transaksi }}</p>
</div>

<div class="grid gap-4 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-brand px-4 py-3 text-white"><h2 class="font-display font-bold">Isi pesanan</h2></div>
            @include('transaksi._detail')
        </section>

        {{-- Pengiriman --}}
        <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="font-display font-bold">Pengiriman</h2>
                @if ($transaksi->status_pembayaran === 'Lunas')
                    <span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ $transaksi->status_pengiriman }}</span>
                @endif
            </div>

            @if ($transaksi->alamat_pengiriman)
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-slate-500">Penerima</dt>
                        <dd class="font-semibold">{{ $transaksi->nama_penerima }}</dd>
                        <dd>{{ $transaksi->telepon_penerima }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Alamat tujuan</dt>
                        <dd class="whitespace-pre-line">{{ $transaksi->alamat_pengiriman }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-sm text-slate-500">Pesanan ini dibuat sebelum fitur alamat pengiriman ada.</p>
            @endif

            @if ($transaksi->bisaDiaturPengirimannya())
                <form method="POST" action="{{ route('admin.transaksi.pengiriman', $transaksi) }}" class="mt-4 grid gap-3 border-t border-slate-100 pt-4 sm:grid-cols-3">
                    @csrf @method('PUT')
                    <div>
                        <label for="estimasi_tiba" class="mb-1 block text-xs font-semibold">Estimasi tiba</label>
                        <input id="estimasi_tiba" type="date" name="estimasi_tiba" required min="{{ $transaksi->tanggal->toDateString() }}" value="{{ old('estimasi_tiba', $transaksi->estimasi_tiba ? $transaksi->estimasi_tiba->toDateString() : '') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                        @error('estimasi_tiba') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="kurir" class="mb-1 block text-xs font-semibold">Kurir</label>
                        <input id="kurir" name="kurir" maxlength="50" placeholder="Misalnya JNE, J&T, SiCepat" value="{{ old('kurir', $transaksi->kurir) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                        @error('kurir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nomor_resi" class="mb-1 block text-xs font-semibold">Nomor resi</label>
                        <input id="nomor_resi" name="nomor_resi" maxlength="50" value="{{ old('nomor_resi', $transaksi->nomor_resi) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                        @error('nomor_resi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex flex-wrap gap-2 sm:col-span-3">
                        <button name="aksi" value="simpan" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Simpan estimasi</button>
                        @if ($transaksi->status_pengiriman !== 'Dikirim')
                            <button name="aksi" value="kirim" class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Tandai sudah dikirim</button>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 sm:col-span-3">Kurir dan nomor resi wajib diisi saat menandai pesanan dikirim. Estimasi tiba terlihat oleh pelanggan.</p>
                </form>
            @elseif ($transaksi->status_pembayaran !== 'Lunas')
                <p class="mt-3 text-xs text-slate-500">Pengiriman bisa diatur setelah pembayaran dikonfirmasi lunas.</p>
            @else
                <dl class="mt-3 grid gap-3 border-t border-slate-100 pt-3 text-sm sm:grid-cols-3">
                    <div><dt class="text-xs text-slate-500">Kurir</dt><dd>{{ $transaksi->kurir ?: '–' }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Nomor resi</dt><dd>{{ $transaksi->nomor_resi ?: '–' }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Dikirim</dt><dd>{{ $transaksi->tanggal_kirim ? $transaksi->tanggal_kirim->format('d/m/Y') : '–' }}</dd></div>
                </dl>
            @endif
        </section>
    </div>

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

        @if ($transaksi->pengiriman)
            @include('admin.transaksi._pengiriman', ['transaksi' => $transaksi, 'pengiriman' => $transaksi->pengiriman])
        @else
            <p class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-600">Cara menerima: <span class="font-semibold">ambil di toko</span>.</p>
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