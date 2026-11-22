<div class="overflow-x-auto">
    <table class="w-full min-w-[32rem] text-left text-sm">
        <thead class="bg-slate-50 text-xs text-slate-600">
            <tr>
                <th class="px-4 py-2.5">Produk</th>
                <th class="px-4 py-2.5">Harga (Rp)</th>
                <th class="px-4 py-2.5">Jumlah</th>
                <th class="px-4 py-2.5 text-right">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach ($transaksi->detail as $d)
                <tr>
                    <td class="px-4 py-3 font-semibold">{{ $d->produk->nama_produk ?? 'Produk dihapus' }}</td>
                    <td class="px-4 py-3">{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">{{ $d->jumlah }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @if ($transaksi->pengiriman)
                <tr class="border-t border-slate-200">
                    <td colspan="3" class="px-4 py-2 text-right text-slate-600">Subtotal produk</td>
                    <td class="px-4 py-2 text-right">Rp {{ number_format($transaksi->subtotal_produk, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right text-slate-600">Ongkos kirim ({{ $transaksi->pengiriman->kurir }})</td>
                    <td class="px-4 py-2 text-right">Rp {{ number_format($transaksi->pengiriman->ongkir, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="border-t border-slate-200">
                <td colspan="3" class="px-4 py-3 text-right font-semibold">Total bayar</td>
                <td class="px-4 py-3 text-right font-display text-lg font-bold">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>