@extends('layouts.katalog')
@section('title', 'Tentang Aplikasi')
@section('content')

<div class="mx-auto max-w-4xl">

    <header class="mb-8">
        <h1 class="font-display text-2xl font-bold text-slate-800">Tentang Gadgetkuori.com</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">
            Sistem informasi penjualan gadget berbasis web yang menghubungkan pembeli dengan pegawai toko
            dalam satu basis data terpusat: dari katalog produk, pemesanan, pembayaran, hingga garansi dan klaim.
        </p>
    </header>

    {{-- 1. LATAR BELAKANG --}}
    <section class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-labelledby="judul-latar">
        <h2 id="judul-latar" class="font-display text-lg font-bold text-slate-800">1. Latar Belakang</h2>
        <div class="mt-3 space-y-3 text-sm leading-relaxed text-slate-600">
            <p>
                Gadget seperti smartphone, laptop, tablet, aksesori, dan smartwatch adalah barang bernilai tinggi
                dengan harga yang sering berubah, stok yang terbatas, serta masa garansi yang menjadi bahan
                pertimbangan utama pembeli. Toko gadget karena itu perlu mencatat banyak hal sekaligus: data
                produk, stok, pelanggan, transaksi, pembayaran, dan garansi.
            </p>
            <p>
                Bila semua pencatatan itu dilakukan manual (buku atau lembar kerja terpisah) dan pembeli harus
                datang ke toko hanya untuk menanyakan harga dan stok, proses menjadi lambat dan rawan salah.
                Gadgetkuori.com dibangun sebagai toko online dengan basis data relasional yang menyatukan data
                tersebut, dilengkapi pembagian peran bagi manajer, kasir, teknisi, dan petugas gudang.
                Pembeli dapat mengambil pesanan di toko atau meminta pesanan dikirim ke alamatnya lewat kurir,
                sehingga belanja bisa dilakukan sepenuhnya secara online.
            </p>
        </div>
    </section>

    {{-- 2. PERMASALAHAN --}}
    <section class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-labelledby="judul-masalah">
        <h2 id="judul-masalah" class="font-display text-lg font-bold text-slate-800">2. Permasalahan</h2>
        <p class="mt-3 text-sm text-slate-600">Aplikasi ini dibuat untuk menjawab permasalahan berikut.</p>
        <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm leading-relaxed text-slate-600">
            <li><strong>Informasi produk tidak mudah diakses.</strong> Pembeli sulit mengetahui harga, merek, dan ketersediaan stok tanpa datang atau bertanya langsung.</li>
            <li><strong>Pencatatan stok dan transaksi manual.</strong> Total belanja bisa salah hitung, stok tidak sinkron dengan penjualan, dan barang yang sama dapat terjual dua kali.</li>
            <li><strong>Pembayaran sulit diverifikasi.</strong> Bukti transfer tersebar di berbagai media dan tidak terhubung ke pesanan.</li>
            <li><strong>Garansi sulit dilacak.</strong> Masa berlaku, ketentuan, dan jumlah klaim tidak tercatat rapi, sehingga klaim rawan sengketa.</li>
            <li><strong>Tidak ada pembagian wewenang.</strong> Siapa pun dapat mengubah data produk, pegawai, atau pelanggan.</li>
            <li><strong>Laporan penjualan dan stok menipis dihitung manual</strong>, sehingga pengambilan keputusan restock terlambat.</li>
            <li><strong>Pembeli di luar kota tidak bisa menerima barang tanpa datang ke toko</strong>, dan pengiriman yang dilakukan manual tidak tercatat (alamat, kurir, nomor resi, status), sehingga sulit dilacak.</li>
        </ol>
    </section>

    {{-- 3. SOLUSI --}}
    <section class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-labelledby="judul-solusi">
        <h2 id="judul-solusi" class="font-display text-lg font-bold text-slate-800">3. Solusi</h2>
        <p class="mt-3 text-sm leading-relaxed text-slate-600">
            Solusinya adalah aplikasi web penjualan gadget (Laravel dan MySQL) dengan sepuluh tabel yang saling
            berelasi: pegawai, pelanggan, kategori, merek, produk, transaksi_penjualan, detail_penjualan,
            pengiriman, garansi, dan klaim_garansi.
        </p>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <caption class="sr-only">Pemetaan permasalahan ke solusi</caption>
                <thead>
                    <tr class="border-b border-slate-200 text-slate-800">
                        <th scope="col" class="py-2 pr-4 font-semibold">Permasalahan</th>
                        <th scope="col" class="py-2 font-semibold">Solusi pada aplikasi</th>
                    </tr>
                </thead>
                <tbody class="align-top text-slate-600">
                    <tr class="border-b border-slate-100">
                        <td class="py-2 pr-4">Informasi produk sulit diakses</td>
                        <td class="py-2">Katalog publik dengan pencarian, filter kategori dan merek, serta status stok.</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2 pr-4">Pencatatan stok dan transaksi manual</td>
                        <td class="py-2">Checkout otomatis: total dihitung sistem, stok berkurang saat pesanan dibuat dan dikembalikan bila dibatalkan, baris produk dikunci agar stok tidak terjual ganda.</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2 pr-4">Pembayaran sulit diverifikasi</td>
                        <td class="py-2">Tiga metode bayar (Transfer Bank, QRIS dengan nominal otomatis, Bayar di Toko), unggah bukti pembayaran, dan konfirmasi lunas oleh pegawai.</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2 pr-4">Garansi sulit dilacak</td>
                        <td class="py-2">Garansi terbit otomatis saat pesanan lunas, lengkap dengan nomor, masa berlaku, ketentuan, dan klaim berstatus.</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2 pr-4">Tidak ada pembagian wewenang</td>
                        <td class="py-2">Akses berdasarkan jabatan pegawai (Manajer, Kasir, Teknisi, Gudang).</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2 pr-4">Laporan dan stok menipis manual</td>
                        <td class="py-2">Dashboard stok, daftar restock, serta laporan penjualan yang dapat diunduh sebagai CSV.</td>
                    </tr>
                    <tr>
                        <td class="py-2 pr-4">Pengiriman tidak tercatat</td>
                        <td class="py-2">Pilihan Ambil di Toko atau Kirim ke Alamat; alamat penerima, kurir, ongkir otomatis, nomor resi, dan pelacakan status sampai pembeli mengonfirmasi barang diterima.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- 4. FITUR --}}
    <section class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-labelledby="judul-fitur">
        <h2 id="judul-fitur" class="font-display text-lg font-bold text-slate-800">4. Fitur Aplikasi</h2>

        <div class="mt-4 grid gap-6 md:grid-cols-2">
            <div>
                <h3 class="font-semibold text-slate-800">Pengunjung dan pelanggan</h3>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm leading-relaxed text-slate-600">
                    <li>Beranda dengan produk terbaru dan ringkasan toko</li>
                    <li>Katalog produk: cari, filter kategori dan merek, hanya yang tersedia</li>
                    <li>Keranjang belanja (tambah, ubah jumlah, hapus)</li>
                    <li>Daftar akun dan masuk dengan email</li>
                    <li>Checkout: pilih ambil di toko atau kirim ke alamat (isi penerima, pilih kurir, ongkir otomatis), lalu pilih metode pembayaran</li>
                    <li>Unggah bukti pembayaran dan batalkan pesanan</li>
                    <li>Riwayat dan detail pesanan, termasuk QRIS dinamis</li>
                    <li>Pelacakan pengiriman (Menunggu Pembayaran, Dikemas, Dikirim, Diterima), nomor resi, dan konfirmasi pesanan diterima</li>
                    <li>Daftar garansi dan pengajuan klaim garansi</li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-slate-800">Pegawai (sesuai jabatan)</h3>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm leading-relaxed text-slate-600">
                    <li><strong>Semua pegawai:</strong> login, dashboard stok, lihat produk, kategori, merek</li>
                    <li><strong>Manajer dan Gudang:</strong> kelola produk, kategori, merek</li>
                    <li><strong>Manajer:</strong> kelola pegawai, laporan penjualan dan unduh CSV</li>
                    <li><strong>Manajer dan Kasir:</strong> kelola pelanggan (tanpa hapus), konfirmasi lunas atau batalkan transaksi, serahkan paket ke kurir dengan nomor resi</li>
                    <li><strong>Manajer dan Teknisi:</strong> proses, selesaikan, atau tolak klaim garansi</li>
                </ul>
            </div>
        </div>
    </section>

</div>

@endsection
