@php
    $tahapan = \App\Models\Pengiriman::TAHAPAN;
    $posisi = $pengiriman->posisiTahap();
    $dibatalkan = $pengiriman->status_pengiriman === \App\Models\Pengiriman::DIBATALKAN;
@endphp
<section class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm" aria-labelledby="judul-pengiriman">
    <h2 id="judul-pengiriman" class="mb-3 font-display font-bold">Pengiriman</h2>

    @if ($dibatalkan)
        <p class="rounded-lg bg-slate-100 px-3 py-2 text-sm text-slate-600">Pengiriman dibatalkan karena pesanan dibatalkan.</p>
    @else
        <ol class="space-y-3">
            @foreach ($tahapan as $i => $tahap)
                @php $selesai = $posisi !== false && $i <= $posisi; @endphp
                <li class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $selesai ? 'bg-brand text-white' : 'bg-slate-200 text-slate-500' }}">{{ $selesai ? '✓' : $i + 1 }}</span>
                    <div class="text-sm">
                        <p class="font-semibold {{ $selesai ? 'text-slate-900' : 'text-slate-400' }}">{{ $tahap }}</p>
                        @if ($tahap === 'Menunggu Pembayaran' && $posisi === 0)
                            <p class="text-xs text-slate-500">Selesaikan pembayaran, lalu tunggu konfirmasi pegawai.</p>
                        @elseif ($tahap === 'Dikemas' && $posisi === 1)
                            <p class="text-xs text-slate-500">Pesanan sedang disiapkan toko.</p>
                        @elseif ($tahap === 'Dikirim' && $selesai && $pengiriman->tanggal_dikirim)
                            <p class="text-xs text-slate-500">{{ $pengiriman->tanggal_dikirim->format('d/m/Y H:i') }} · {{ $pengiriman->kurir }}</p>
                        @elseif ($tahap === 'Diterima' && $selesai && $pengiriman->tanggal_diterima)
                            <p class="text-xs text-slate-500">{{ $pengiriman->tanggal_diterima->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    @endif

    <dl class="mt-4 divide-y divide-slate-100 border-t border-slate-100 text-sm">
        <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Kurir</dt><dd class="text-right">{{ $pengiriman->kurir }}</dd></div>
        <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">No. resi</dt><dd class="text-right font-semibold">{{ $pengiriman->no_resi ?: 'Belum tersedia' }}</dd></div>
        <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Penerima</dt><dd class="text-right">{{ $pengiriman->nama_penerima }} ({{ $pengiriman->telepon_penerima }})</dd></div>
        <div class="py-2"><dt class="text-slate-600">Alamat</dt><dd class="mt-1">{{ $pengiriman->alamat_tujuan }}</dd></div>
    </dl>

    @if ($pengiriman->status_pengiriman === \App\Models\Pengiriman::DIKIRIM)
        <form method="POST" action="{{ route('pesanan.terima', $transaksi) }}" class="mt-3" onsubmit="return confirm('Pastikan barang sudah Anda terima dalam kondisi baik. Lanjutkan?')">
            @csrf
            <button class="w-full rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand">Pesanan sudah saya terima</button>
        </form>
    @endif
</section>
