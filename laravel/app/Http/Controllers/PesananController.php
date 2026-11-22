<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Produk;
use App\Models\TransaksiPenjualan;
use App\Services\Qris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PesananController extends Controller
{
    /** Riwayat pesanan milik pelanggan yang sedang login. */
    public function index()
    {
        $pesanan = TransaksiPenjualan::with('pengiriman')
            ->where('id_pelanggan', auth('pelanggan')->id())
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('pesanan.index', compact('pesanan'));
    }

    public function show(TransaksiPenjualan $transaksi)
    {
        $this->pastikanMilikSendiri($transaksi);
        $transaksi->load(['detail.produk', 'pengiriman']);

        // QRIS dinamis: nominalnya otomatis sama dengan total pesanan
        $qrisPayload = null;
        $qrisError = false;
        if ($transaksi->bisaDiproses() && $transaksi->metode_pembayaran === 'QRIS' && config('pembayaran.qris_statis')) {
            try {
                $qrisPayload = Qris::dinamis(config('pembayaran.qris_statis'), (int) $transaksi->total_bayar);
            } catch (\InvalidArgumentException $e) {
                $qrisError = true; // teks QRIS di .env tidak valid
            }
        }

        return view('pesanan.show', compact('transaksi', 'qrisPayload', 'qrisError'));
    }

    /** Checkout: ubah isi keranjang menjadi transaksi. */
    public function store(Request $request)
    {
        $kirimJika = 'required_if:penerimaan,' . Pengiriman::KIRIM;

        $data = $request->validate([
            'metode_pembayaran' => ['required', Rule::in(TransaksiPenjualan::METODE)],
            'penerimaan'        => ['required', Rule::in(Pengiriman::CARA)],
            'nama_penerima'     => [$kirimJika, 'nullable', 'string', 'max:100'],
            'telepon_penerima'  => [$kirimJika, 'nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'alamat_tujuan'     => [$kirimJika, 'nullable', 'string', 'min:10', 'max:500'],
            'kurir'             => [$kirimJika, 'nullable', Rule::in(array_keys(Pengiriman::daftarKurir()))],
        ], [
            'penerimaan.required'          => 'Pilih cara menerima pesanan.',
            'nama_penerima.required_if'    => 'Nama penerima wajib diisi untuk pesanan yang dikirim.',
            'telepon_penerima.required_if' => 'Nomor telepon penerima wajib diisi.',
            'telepon_penerima.regex'       => 'Nomor telepon hanya boleh berisi angka, spasi, tanda + atau -, 8 sampai 20 karakter.',
            'alamat_tujuan.required_if'    => 'Alamat pengiriman wajib diisi.',
            'alamat_tujuan.min'            => 'Alamat pengiriman minimal 10 karakter (jalan, nomor, kota, kode pos).',
            'kurir.required_if'            => 'Pilih kurir pengiriman.',
            'kurir.in'                     => 'Kurir yang dipilih tidak tersedia.',
        ]);

        $kirim = $data['penerimaan'] === Pengiriman::KIRIM;

        // Pesanan yang dikirim tidak bisa dibayar di toko
        if ($kirim && $data['metode_pembayaran'] === 'Bayar di Toko') {
            throw ValidationException::withMessages([
                'metode_pembayaran' => 'Pesanan yang dikirim harus dibayar lewat Transfer Bank atau QRIS.',
            ]);
        }

        $ongkir = $kirim ? (int) Pengiriman::daftarKurir()[$data['kurir']]['tarif'] : 0;

        $keranjang = session('keranjang', []);
        if (empty($keranjang)) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang masih kosong.');
        }

        try {
            $transaksi = DB::transaction(function () use ($keranjang, $data, $kirim, $ongkir) {
                $pk = (new Produk)->getKeyName();

                // Kunci baris produk supaya dua pembeli tidak mengambil stok yang sama
                $produk = Produk::whereIn($pk, array_keys($keranjang))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy($pk);

                $total = 0;
                $baris = [];

                foreach ($keranjang as $id => $jumlah) {
                    $p = $produk->get($id);

                    if (! $p || $p->stok < $jumlah) {
                        throw new \RuntimeException(
                            ($p->nama_produk ?? 'Salah satu produk') . ' stoknya tidak cukup atau sudah tidak tersedia. Periksa keranjang Anda.'
                        );
                    }

                    $subtotal = $p->harga * $jumlah;
                    $total += $subtotal;

                    $baris[] = [
                        'id_produk'    => $p->getKey(),
                        'jumlah'       => $jumlah,
                        'harga_satuan' => $p->harga,
                        'subtotal'     => $subtotal,
                    ];

                    $p->decrement('stok', $jumlah);
                }

                $transaksi = TransaksiPenjualan::create([
                    'tanggal'           => now(),
                    'metode_pembayaran' => $data['metode_pembayaran'],
                    'status_pembayaran' => TransaksiPenjualan::BELUM_DIBAYAR,
                    'total_bayar'       => $total + $ongkir,
                    'id_pelanggan'      => auth('pelanggan')->id(),
                ]);

                $transaksi->detail()->createMany($baris);

                if ($kirim) {
                    $transaksi->pengiriman()->create([
                        'nama_penerima'     => $data['nama_penerima'],
                        'telepon_penerima'  => $data['telepon_penerima'],
                        'alamat_tujuan'     => $data['alamat_tujuan'],
                        'kurir'             => $data['kurir'],
                        'ongkir'            => $ongkir,
                        'status_pengiriman' => Pengiriman::MENUNGGU,
                    ]);
                }

                return $transaksi;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('keranjang.index')->with('error', $e->getMessage());
        }

        session()->forget('keranjang');

        return redirect()->route('pesanan.show', $transaksi)
            ->with('sukses', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran, lalu tunggu konfirmasi dari pegawai.');
    }

    public function unggahBukti(Request $request, TransaksiPenjualan $transaksi)
    {
        $this->pastikanMilikSendiri($transaksi);

        if (! $transaksi->bisaUnggahBukti()) {
            return back()->with('error', 'Bukti pembayaran tidak bisa diunggah untuk pesanan ini.');
        }

        $request->validate([
            'bukti' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'bukti.required' => 'Pilih foto bukti pembayaran dulu.',
            'bukti.image'    => 'File harus berupa gambar.',
            'bukti.mimes'    => 'Format yang diterima: JPG atau PNG.',
            'bukti.max'      => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($transaksi->bukti_pembayaran) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
        }

        $path = $request->file('bukti')->store('bukti-pembayaran', 'public');
        $transaksi->update(['bukti_pembayaran' => $path]);

        return back()->with('sukses', 'Bukti pembayaran berhasil diunggah. Pegawai akan memeriksanya.');
    }

    public function batal(TransaksiPenjualan $transaksi)
    {
        $this->pastikanMilikSendiri($transaksi);

        if (! $transaksi->batalkan()) {
            return back()->with('error', 'Pesanan ini sudah diproses dan tidak bisa dibatalkan.');
        }

        return back()->with('sukses', 'Pesanan dibatalkan.');
    }

    /** Pelanggan mengonfirmasi barang sudah sampai di tangannya. */
    public function terima(TransaksiPenjualan $transaksi)
    {
        $this->pastikanMilikSendiri($transaksi);

        $pengiriman = $transaksi->pengiriman;

        if (! $pengiriman || $pengiriman->status_pengiriman !== Pengiriman::DIKIRIM) {
            return back()->with('error', 'Pesanan ini belum berstatus Dikirim.');
        }

        $pengiriman->update([
            'status_pengiriman' => Pengiriman::DITERIMA,
            'tanggal_diterima'  => now(),
        ]);

        return back()->with('sukses', 'Terima kasih! Pesanan ditandai sudah Anda terima.');
    }

    private function pastikanMilikSendiri(TransaksiPenjualan $transaksi): void
    {
        abort_unless($transaksi->id_pelanggan === auth('pelanggan')->id(), 404);
    }
}