@extends('layouts.katalog')
@section('title', 'Garansi Saya')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Garansi saya</h1>
    <p class="text-sm text-slate-500">Nomor garansi terbit otomatis setelah pembayaran pesanan dikonfirmasi lunas.</p>
</div>

<section class="mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <h2 class="border-b border-slate-200 px-4 py-3 font-display font-bold">Daftar garansi</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[44rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">Nomor garansi</th>
                    <th class="px-4 py-2.5">Produk</th>
                    <th class="px-4 py-2.5">Berlaku sampai</th>
                    <th class="px-4 py-2.5">Status</th>
                    <th class="px-4 py-2.5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($garansi as $g)
                    @php
                        $aktif    = $g->aktif();
                        $berjalan = $g->klaim->contains(fn ($k) => $k->masihBerjalan());
                        $habis = $g->jatahKlaimHabis();
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold">{{ $g->nomor_garansi }}</td>
                        <td class="px-4 py-3">{{ $g->detail->produk->nama_produk ?? 'Produk dihapus' }}</td>
                        <td class="px-4 py-3">{{ $g->tanggal_berakhir->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @if ($aktif)
                                <span class="rounded bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800">Aktif</span>
                                <span class="ml-1 text-xs text-slate-500">sisa {{ $g->sisaHari() }} hari</span>
                            @else
                                <span class="rounded bg-slate-200 px-2 py-0.5 text-xs font-semibold text-slate-600">Berakhir</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                           @if ($aktif && ! $berjalan && ! $habis)
                                <a href="{{ route('garansi.klaim.form', $g) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Ajukan klaim</a>
                            @elseif ($berjalan)
                                <span class="text-xs text-slate-500">Klaim sedang diproses</span>
                            @elseif ($habis)
                                <span class="text-xs text-slate-500">Jatah klaim sudah terpakai</span>
                            @else
                                <span class="text-xs text-slate-400">–</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada garansi. Garansi terbit setelah pesanan Anda dikonfirmasi lunas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <h2 class="border-b border-slate-200 px-4 py-3 font-display font-bold">Riwayat klaim</h2>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[44rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">Tanggal</th>
                    <th class="px-4 py-2.5">Produk</th>
                    <th class="px-4 py-2.5">Keluhan</th>
                    <th class="px-4 py-2.5">Status</th>
                    <th class="px-4 py-2.5">Keterangan dari toko</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($riwayatKlaim as $k)
                    <tr class="align-top hover:bg-slate-50">
                        <td class="px-4 py-3">{{ $k->tanggal_klaim->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $k->garansi->detail->produk->nama_produk ?? 'Produk dihapus' }}</p>
                            <p class="text-xs text-slate-500">{{ $k->garansi->nomor_garansi }}</p>
                        </td>
                        <td class="max-w-[16rem] px-4 py-3">{{ $k->keluhan }}</td>
                        <td class="px-4 py-3">
                            @include('garansi._status-klaim', ['klaim' => $k])
                            @if ($k->tanggal_selesai)
                                <p class="mt-1 text-xs text-slate-500">Selesai {{ $k->tanggal_selesai->format('d/m/Y') }}</p>
                            @endif
                        </td>
                        <td class="max-w-[16rem] px-4 py-3">{{ $k->keterangan ?: '–' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada klaim yang diajukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection