# Gadgetkuori.com

Sistem informasi penjualan gadget berbasis web (Laravel 10 + MySQL) untuk Project Basis Data Kelas A, Kelompok Dahlah-terserah.

## 1. Latar Belakang

Gadget (smartphone, laptop, tablet, aksesori, smartwatch) adalah barang bernilai tinggi dengan harga yang sering berubah, stok terbatas, dan masa garansi yang menjadi pertimbangan utama pembeli. Toko gadget perlu mencatat data produk, stok, pelanggan, transaksi, pembayaran, dan garansi sekaligus. Bila dilakukan manual dan pembeli harus datang ke toko hanya untuk menanyakan harga dan stok, proses menjadi lambat dan rawan salah. Gadgetkuori.com dibangun sebagai toko online dengan basis data relasional yang menyatukan seluruh data tersebut, dengan pembagian peran untuk Manajer, Kasir, Teknisi, dan Gudang. Pembeli dapat mengambil pesanan di toko atau meminta pesanan dikirim ke alamatnya lewat kurir.

## 2. Permasalahan

1. Informasi harga, merek, dan stok produk tidak mudah diakses pembeli.
2. Pencatatan stok dan transaksi manual: total salah hitung, stok tidak sinkron, barang bisa terjual ganda.
3. Bukti pembayaran tidak terhubung ke pesanan sehingga sulit diverifikasi.
4. Garansi sulit dilacak: masa berlaku, ketentuan, dan jumlah klaim tidak tercatat rapi.
5. Tidak ada pembagian wewenang antar pegawai.
6. Laporan penjualan dan pemantauan stok menipis dilakukan manual.
7. Pembeli di luar kota tidak bisa menerima barang tanpa datang ke toko, dan pengiriman manual tidak tercatat (alamat, kurir, resi, status).

## 3. Solusi

Aplikasi web penjualan gadget dengan sepuluh tabel yang saling berelasi (`pegawai`, `pelanggan`, `kategori`, `merek`, `produk`, `transaksi_penjualan`, `detail_penjualan`, `pengiriman`, `garansi`, `klaim_garansi`).

| Permasalahan | Solusi pada aplikasi |
|---|---|
| Informasi produk sulit diakses | Katalog publik dengan pencarian, filter, dan status stok |
| Pencatatan manual | Checkout otomatis; stok dikurangi saat pesanan dibuat, dikembalikan saat batal; baris produk dikunci (`lockForUpdate`) agar tidak terjual ganda |
| Pembayaran sulit diverifikasi | Transfer Bank, QRIS dinamis, Bayar di Toko; unggah bukti; konfirmasi lunas oleh pegawai |
| Garansi sulit dilacak | Garansi terbit otomatis saat lunas; nomor, masa berlaku, ketentuan, klaim berstatus |
| Tidak ada pembagian wewenang | Middleware `CekJabatan` berdasarkan jabatan pegawai |
| Laporan manual | Dashboard stok dan laporan penjualan (unduh CSV) |
| Pengiriman tidak tercatat | Pilihan Ambil di Toko / Kirim ke Alamat, ongkir otomatis per kurir (`config/pengiriman.php`), nomor resi, pelacakan status, konfirmasi diterima |

## 4. Fitur

**Pengunjung dan pelanggan**
- Beranda, katalog (cari, filter kategori dan merek, hanya stok tersedia)
- Keranjang belanja (tambah, ubah jumlah, hapus)
- Daftar akun dan login (email)
- Checkout: pilih ambil di toko atau kirim ke alamat (penerima, alamat, kurir, ongkir otomatis), pilih metode pembayaran, unggah bukti, batalkan pesanan
- Pelacakan pengiriman (Menunggu Pembayaran, Dikemas, Dikirim, Diterima), nomor resi, dan tombol "Pesanan sudah saya terima"
- Riwayat dan detail pesanan (QRIS dinamis)
- Daftar garansi dan pengajuan klaim (satu klaim berjalan, batas klaim per garansi diatur di `config/garansi.php`)
- Halaman Tentang (`/tentang`)

**Pegawai**

| Jabatan | Hak akses |
|---|---|
| Semua pegawai | Login, dashboard stok, lihat produk, kategori, merek |
| Manajer, Gudang | Kelola produk, kategori, merek |
| Manajer | Kelola pegawai, laporan penjualan dan CSV |
| Manajer, Kasir | Kelola pelanggan (tanpa hapus), konfirmasi lunas atau batalkan transaksi, serahkan paket ke kurir (isi nomor resi) |
| Manajer, Teknisi | Proses, selesaikan, atau tolak klaim garansi |

## Menjalankan

```bash
composer install
cp .env.example .env        # lalu atur DB_DATABASE, NAMA_TOKO, QRIS_STATIS, REKENING_TOKO
php artisan key:generate
php artisan migrate --seed   # jika database sudah ada: php artisan migrate (menambah tabel pengiriman)
php artisan storage:link
php artisan serve
```

Akun contoh dari seeder (password `password123`): pegawai `admin.ahmad` (Manajer), `budi.santoso` (Kasir), `teknisi.doni` (Teknisi), `gudang.hendra` (Gudang); pelanggan `andi.pratama@email.com`. Login pegawai di `/pegawai/login`.

## Alur pengiriman (versi online)

1. Pelanggan memilih **Kirim ke alamat** saat checkout, mengisi penerima dan alamat, memilih kurir; ongkir ditambahkan otomatis ke total. Pesanan yang dikirim hanya bisa dibayar lewat Transfer Bank atau QRIS.
2. Pesanan masuk status pengiriman **Menunggu Pembayaran**. Setelah pegawai mengonfirmasi lunas, status menjadi **Dikemas** (garansi terbit bersamaan).
3. Kasir atau Manajer menyerahkan paket ke kurir dan mengisi nomor resi; status menjadi **Dikirim**.
4. Pelanggan melacak status dan nomor resi di halaman pesanan, lalu menekan **Pesanan sudah saya terima**; status menjadi **Diterima**.
5. Pesanan lama tanpa data pengiriman dianggap ambil di toko. Pendapatan pada laporan penjualan dihitung dari produk saja (tanpa ongkir).

## Diagram

ERD dan diagram UML ada di folder `docs/diagram/`.
