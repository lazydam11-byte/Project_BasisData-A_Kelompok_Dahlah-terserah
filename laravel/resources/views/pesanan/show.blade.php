@extends('layouts.katalog')
@section('title', 'Pesanan #' . $transaksi->id_transaksi)

@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-2">
    <div>
        <h1 class="font-display text-2xl font-bold">Pesanan #{{ $transaksi->id_transaksi }}</h1>
        <p class="text-sm text-slate-500">{{ $transaksi->tanggal->format('d/m/Y H:i') }} · {{ $transaksi->metode_pembayaran }}</p>
    </div>
    @include('transaksi._status', ['transaksi' => $transaksi])
</div>

@if ($transaksi->status_pembayaran === 'Lunas')
    <div class="mb-4 rounded-xl border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-900">
        Pembayaran sudah dikonfirmasi. Pesanan akan dikemas dan dikirim ke alamat Anda.
    </div>
@endif

<div class="grid gap-4 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @include('transaksi._detail')
        </section>
    </div>

    <aside class="space-y-4">
        {{-- Pengiriman --}}
        @if ($transaksi->alamat_pengiriman)
            <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="mb-2 font-display font-bold">Pengiriman</h2>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-xs text-slate-500">Penerima</dt>
                        <dd class="font-semibold">{{ $transaksi->nama_penerima }} · {{ $transaksi->telepon_penerima }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Alamat</dt>
                        <dd class="whitespace-pre-line">{{ $transaksi->alamat_pengiriman }}</dd>
                    </div>

                    @if ($transaksi->status_pembayaran === 'Lunas')
                        <div>
                            <dt class="text-xs text-slate-500">Status pengiriman</dt>
                            <dd class="font-semibold">{{ $transaksi->status_pengiriman }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Estimasi tiba</dt>
                            <dd>{{ $transaksi->estimasi_tiba ? $transaksi->estimasi_tiba->format('d/m/Y') : 'Belum ditentukan pegawai' }}</dd>
                        </div>
                        @if ($transaksi->kurir || $transaksi->nomor_resi)
                            <div>
                                <dt class="text-xs text-slate-500">Kurir dan nomor resi</dt>
                                <dd>{{ $transaksi->kurir ?: '–' }} · {{ $transaksi->nomor_resi ?: '–' }}</dd>
                            </div>
                        @endif
                    @endif
                </dl>

                @if ($transaksi->bisaDiterima())
                    <form method="POST" action="{{ route('pesanan.terima', $transaksi) }}" class="mt-3" onsubmit="return confirm('Konfirmasi pesanan sudah Anda terima?')">
                        @csrf
                        <button class="w-full rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Pesanan sudah saya terima</button>
                    </form>
                @endif
            </section>
        @endif

        {{-- Petunjuk pembayaran --}}
        @if ($transaksi->bisaDiproses())
            <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="mb-2 font-display font-bold">Cara membayar</h2>

                @if ($transaksi->metode_pembayaran === 'QRIS')
                    @if ($qrisPayload)
                        <div class="flex justify-center rounded-lg border border-slate-200 bg-white p-3">
                            <div id="qris-kode" data-payload="{{ $qrisPayload }}" role="img" aria-label="Kode QRIS pesanan {{ $transaksi->id_transaksi }}"></div>
                        </div>
                        <p class="mt-3 text-center text-xs text-slate-500">{{ config('pembayaran.nama_toko') }}</p>
                        <p class="text-center font-display text-xl font-bold">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</p>
                        <ol class="mt-3 list-decimal space-y-1 pl-5 text-sm text-slate-600">
                            <li>Buka aplikasi bank atau e-wallet, pilih Scan QRIS.</li>
                            <li>Scan kode di atas. Nominal sudah terisi otomatis.</li>
                            <li>Selesaikan pembayaran, lalu unggah bukti di bawah.</li>
                        </ol>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                        <script>
                            (function () {
                                var el = document.getElementById('qris-kode');
                                new QRCode(el, { text: el.dataset.payload, width: 240, height: 240, correctLevel: QRCode.CorrectLevel.M });
                            })();
                        </script>
                    @elseif ($qrisError)
                        <p class="text-sm text-red-700">Teks QRIS toko di pengaturan tidak valid. Hubungi pegawai untuk menyelesaikan pembayaran.</p>
                    @else
                        <p class="text-sm text-slate-600">QRIS toko belum diatur. Hubungi pegawai untuk menyelesaikan pembayaran.</p>
                    @endif

                @elseif ($transaksi->metode_pembayaran === 'Transfer Bank')
                    @if (config('pembayaran.rekening'))
                        <p class="text-sm text-slate-600">Transfer tepat sebesar</p>
                        <p class="font-display text-xl font-bold">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</p>
                        <p class="mt-2 text-sm text-slate-600">ke rekening:</p>
                        <p class="rounded-lg bg-slate-50 px-3 py-2 text-sm font-semibold">{{ config('pembayaran.rekening') }}</p>
                        <p class="mt-3 text-sm text-slate-600">Setelah transfer, unggah bukti di bawah.</p>
                    @else
                        <p class="text-sm text-slate-600">Informasi rekening belum diatur. Hubungi pegawai untuk menyelesaikan pembayaran.</p>
                    @endif

                @else
                    <p class="text-sm text-slate-600">Bayar langsung di toko sebesar <span class="font-semibold">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span> dengan menyebutkan nomor pesanan #{{ $transaksi->id_transaksi }}.</p>
                @endif
            </section>
        @endif

        {{-- Bukti pembayaran --}}
        @if ($transaksi->bisaUnggahBukti() || $transaksi->bukti_pembayaran)
            <section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="mb-2 font-display font-bold">Bukti pembayaran</h2>

                @if ($transaksi->bukti_pembayaran)
                    <a href="{{ $transaksi->bukti_url }}" target="_blank" rel="noopener">
                        <img src="{{ $transaksi->bukti_url }}" alt="Bukti pembayaran pesanan {{ $transaksi->id_transaksi }}" class="mb-3 max-h-64 w-full rounded-lg border border-slate-200 object-contain">
                    </a>
                @endif

                @if ($transaksi->bisaUnggahBukti())
                    <form method="POST" action="{{ route('pesanan.bukti', $transaksi) }}" enctype="multipart/form-data">
                        @csrf
                        <label for="bukti" class="mb-1 block text-xs font-semibold">{{ $transaksi->bukti_pembayaran ? 'Ganti foto bukti' : 'Unggah foto bukti' }} (JPG/PNG, maks. 2 MB)</label>
                        <input id="bukti" type="file" name="bukti" accept="image/png,image/jpeg" required class="w-full text-sm file:mr-3 file:rounded file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold hover:file:bg-slate-200">
                        @error('bukti') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <button class="mt-3 w-full rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Kirim bukti</button>
                    </form>
                    <p class="mt-2 text-xs text-slate-500">Pesanan dikirim setelah pegawai mengonfirmasi pembayaran.</p>
                @endif
            </section>
        @endif
    </aside>
</div>

<div class="mt-4 flex flex-wrap items-center gap-3">
    <a href="{{ route('pesanan.index') }}" class="text-sm text-slate-500 hover:underline">Kembali ke daftar pesanan</a>
    @if ($transaksi->bisaDiproses())
        <form method="POST" action="{{ route('pesanan.batal', $transaksi) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
            @csrf
            <button class="rounded bg-red-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-red-700">Batalkan pesanan</button>
        </form>
    @endif
</div>
@endsection