@php $dibatalkan = $pengiriman->status_pengiriman === \App\Models\Pengiriman::DIBATALKAN; @endphp
<div class="mt-4 border-t border-slate-100 pt-4">
    <h3 class="mb-2 text-xs font-semibold text-slate-600">Pengiriman</h3>
    <dl class="divide-y divide-slate-100 text-sm">
        <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Status</dt><dd class="font-semibold">{{ $pengiriman->status_pengiriman }}</dd></div>
        <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Kurir</dt><dd class="text-right">{{ $pengiriman->kurir }}</dd></div>
        <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Ongkir</dt><dd>Rp {{ number_format($pengiriman->ongkir, 0, ',', '.') }}</dd></div>
        <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Penerima</dt><dd class="text-right">{{ $pengiriman->nama_penerima }} ({{ $pengiriman->telepon_penerima }})</dd></div>
        <div class="py-2"><dt class="text-slate-600">Alamat</dt><dd class="mt-1">{{ $pengiriman->alamat_tujuan }}</dd></div>
        <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">No. resi</dt><dd class="font-semibold">{{ $pengiriman->no_resi ?: '–' }}</dd></div>
        @if ($pengiriman->tanggal_dikirim)
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Dikirim</dt><dd>{{ $pengiriman->tanggal_dikirim->format('d/m/Y H:i') }}</dd></div>
        @endif
        @if ($pengiriman->tanggal_diterima)
            <div class="flex justify-between gap-3 py-2"><dt class="text-slate-600">Diterima</dt><dd>{{ $pengiriman->tanggal_diterima->format('d/m/Y H:i') }}</dd></div>
        @endif
    </dl>

    @if ($pengiriman->status_pengiriman === \App\Models\Pengiriman::DIKEMAS)
        <form method="POST" action="{{ route('admin.transaksi.update', $transaksi) }}" class="mt-3" onsubmit="return confirm('Serahkan paket ke kurir dengan nomor resi ini?')">
            @csrf @method('PUT')
            <input type="hidden" name="aksi" value="kirim">
            <label for="no_resi" class="mb-1 block text-xs font-semibold">Nomor resi dari {{ $pengiriman->kurir }}</label>
            <input id="no_resi" type="text" name="no_resi" maxlength="40" required value="{{ old('no_resi') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
            @error('no_resi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            <button class="mt-2 w-full rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Serahkan ke kurir</button>
        </form>
    @elseif ($pengiriman->status_pengiriman === \App\Models\Pengiriman::MENUNGGU)
        <p class="mt-2 text-xs text-slate-500">Konfirmasi lunas dulu; pesanan lalu masuk tahap Dikemas.</p>
    @endif
</div>
