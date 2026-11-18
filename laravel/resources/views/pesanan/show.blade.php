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
        Pembayaran sudah dikonfirmasi. Silakan ambil pesanan di toko dengan menyebutkan nomor pesanan.
    </div>
@endif

<div class="grid gap-4 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @include('transaksi._detail')
        </section>
    </div>

    <aside class="space-y-4">
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
                    <p class="mt-2 text-xs text-slate-500">Pesanan diambil di toko setelah pegawai mengonfirmasi pembayaran.</p>
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