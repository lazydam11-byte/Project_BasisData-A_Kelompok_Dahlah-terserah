@php
    $kelas = match ($transaksi->status_pembayaran) {
        'Lunas'      => 'bg-green-100 text-green-800',
        'Dibatalkan' => 'bg-slate-200 text-slate-600',
        default      => 'bg-amber-100 text-amber-800',
    };
@endphp
<span class="rounded px-2 py-0.5 text-xs font-semibold {{ $kelas }}">{{ $transaksi->status_pembayaran }}</span>