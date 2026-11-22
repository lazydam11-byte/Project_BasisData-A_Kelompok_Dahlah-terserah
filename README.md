# Gadgetkuori.com

**Sistem Basis Data Toko Gadget Online** — aplikasi web penjualan gadget (smartphone, laptop, tablet, aksesori, smartwatch) lengkap dengan pembayaran, pengiriman, garansi, dan klaim garansi.

Project Basis Data Kelas A, Kelompok **Dahlah terserah**.

| No | Nama                   | NPM        |
| -- | ---------------------- | ---------- |
| 1  | Harisa Malfa Liya      | 4525210027 |
| 2  | Karel Abimanyu Ahpandi | 4525210031 |
| 3  | Khairul Adam Efendi    | 4525210035 |
| 4  | Larasati Dewi          | 4525210037 |
| 5  | Muhammad Haikal Azhari | 4525210046 |
| 6  | Adelita Daeli          | 4525210049 |

---

## Daftar Isi

1. [Gambaran Umum](#1-gambaran-umum)
2. [Fitur](#2-fitur)
3. [Hak Akses Pegawai](#3-hak-akses-pegawai)
4. [Teknologi](#4-teknologi)
5. [Struktur Database](#5-struktur-database)
6. [Alur Pesanan](#6-alur-pesanan)
7. [Cara Menjalankan](#7-cara-menjalankan)
8. [Konfigurasi](#8-konfigurasi)
9. [Struktur Folder](#9-struktur-folder)
10. [Dokumentasi dan Diagram](#10-dokumentasi-dan-diagram)
11. [Batasan Sistem](#11-batasan-sistem)

---

## 1. Gambaran Umum

Gadgetkuori.com melayani dua kelompok pengguna:

- **Pembeli** (pengunjung dan pelanggan): melihat katalog, berbelanja, membayar, melacak pengiriman, dan mengajukan klaim garansi.
- **Pegawai** dengan empat jabatan: **Manajer**, **Kasir**, **Teknisi**, dan **Gudang**. Fitur yang bisa dibuka dibatasi menurut jabatan.

Pembeli dan pegawai memakai halaman login yang terpisah (guard `pelanggan` dan `pegawai`).

## 2. Fitur

### Pengunjung dan pelanggan

- Beranda berisi produk terbaru dan ringkasan toko.
- Katalog dengan pencarian nama, filter kategori dan merek, serta pilihan hanya produk yang stoknya tersedia. Setiap produk berlabel status stok: *Stok tersedia*, *Stok terbatas* (3 unit atau kurang), atau *Stok habis*.
- Keranjang belanja (disimpan di session browser, bukan di database): tambah, ubah jumlah (dibatasi stok), dan hapus.
- Pendaftaran akun (kata sandi minimal 8 karakter, dibatasi 10 permintaan per menit) dan login dengan email. Setelah mendaftar, pelanggan langsung masuk.
- Checkout: isi nama, telepon, dan alamat penerima, lalu pilih metode pembayaran **Transfer Bank** atau **QRIS**.
- QRIS dinamis: kode QR dibuat dari QRIS statis toko dengan nominal yang otomatis sama dengan total pesanan.
- Unggah bukti pembayaran (JPG/PNG, maksimal 2 MB) dan pembatalan pesanan yang belum dibayar.
- Riwayat dan detail pesanan, termasuk pelacakan pengiriman (kurir, nomor resi, estimasi tiba) dan tombol **Pesanan sudah saya terima**.
- Daftar garansi dan pengajuan klaim garansi.

### Pegawai

- Dashboard stok: ringkasan (jumlah produk, total unit, nilai persediaan, jumlah pelanggan), produk habis, stok menipis (batas ≤ 5 unit), daftar restock (8 produk teratas), stok per kategori, dan produk terbaru.
- CRUD produk (foto JPG/PNG maksimal 2 MB, lama garansi 0–120 bulan), kategori, dan merek.
- CRUD pegawai (kata sandi minimal 6 karakter) dan pengelolaan pelanggan (tanpa hapus). Kolom `username` pelanggan hanya diisi lewat menu ini dan tidak dipakai untuk login, sebab pelanggan masuk dengan email.
- Konfirmasi lunas atau pembatalan transaksi; pembatalan mengembalikan stok.
- Pengaturan pengiriman: estimasi tiba, kurir, nomor resi, lalu tandai dikirim.
- Penanganan klaim garansi: Diajukan → Diproses → Selesai atau Ditolak.
- Laporan penjualan per periode (bawaan: awal bulan sampai hari ini) dan metode pembayaran, berisi ringkasan (jumlah transaksi, pendapatan, unit terjual, rata-rata per transaksi), rekap per metode, dan 5 produk terlaris. Hanya transaksi berstatus Lunas yang dihitung, dan laporan dapat diunduh sebagai CSV.

### Otomatis di belakang layar

- **Stok aman dari penjualan ganda**: baris produk dikunci (`lockForUpdate`) saat checkout.
- **Harga tersimpan per transaksi**: `harga_satuan` pada detail penjualan tidak berubah walau harga produk diubah.
- **Garansi terbit otomatis** untuk setiap barang bergaransi saat transaksi dikonfirmasi lunas.
- **Status pengiriman mengikuti alur**: lunas → *Dikemas*, ditandai dikirim → *Dikirim*, dikonfirmasi pembeli → *Diterima*.
- **Aturan klaim**: garansi harus masih aktif, tidak ada klaim yang sedang berjalan, dan jatah klaim belum habis (saat ini 1 klaim per garansi; klaim yang ditolak tidak dihitung).
- **Aturan penghapusan data**: kategori dan merek tidak dapat dihapus selama masih dipakai produk; pegawai tidak dapat menghapus akunnya sendiri atau Manajer terakhir, dan pegawai yang sudah tercatat pada transaksi atau klaim ditolak oleh foreign key; produk yang sudah pernah terjual juga ditolak oleh foreign key karena terhubung ke detail penjualan.

## 3. Hak Akses Pegawai

| Jabatan             | Fitur yang dapat diakses |
| ------------------- | ------------------------ |
| Semua pegawai       | Login, dashboard stok, melihat daftar produk, kategori, dan merek |
| Manajer, Gudang     | Tambah, ubah, hapus produk, kategori, dan merek |
| Manajer             | Kelola pegawai; laporan penjualan dan unduh CSV |
| Manajer, Kasir      | Lihat, tambah, ubah pelanggan (tanpa hapus); konfirmasi lunas atau batalkan transaksi; atur pengiriman |
| Manajer, Teknisi    | Proses, selesaikan, atau tolak klaim garansi |

Aturan ini didefinisikan pada konstanta `KELOLA_*` di `app/Models/Pegawai.php` dan ditegakkan oleh middleware `CekJabatan` pada `routes/web.php`.

### Rute utama

| Alamat | Akses | Keterangan |
| ------ | ----- | ---------- |
| `/` | Umum | Beranda |
| `/katalog` | Umum | Katalog produk |
| `/keranjang` | Umum | Keranjang belanja |
| `/daftar`, `/login` | Umum | Pendaftaran dan login pelanggan |
| `/pesanan`, `/pesanan/{id}` | Pelanggan | Riwayat dan detail pesanan, unggah bukti, batal, konfirmasi diterima |
| `/garansi` | Pelanggan | Daftar garansi dan pengajuan klaim |
| `/pegawai/login` | Umum | Login pegawai |
| `/admin/dashboard` | Pegawai | Dashboard stok |
| `/admin/produk`, `/admin/kategori`, `/admin/merek` | Pegawai (ubah: Manajer, Gudang) | Data master |
| `/admin/pegawai` | Manajer | Pengelolaan pegawai |
| `/admin/pelanggan` | Manajer, Kasir | Pengelolaan pelanggan |
| `/admin/transaksi` | Manajer, Kasir | Konfirmasi pembayaran dan pengiriman |
| `/admin/klaim` | Manajer, Teknisi | Penanganan klaim garansi |
| `/admin/laporan/penjualan` | Manajer | Laporan penjualan dan unduh CSV |
| `/media/{path}` | Umum | Menyajikan foto produk dari folder `produk/` |

## 4. Teknologi

| Komponen   | Keterangan |
| ---------- | ---------- |
| Bahasa     | PHP 8.1+ |
| Framework  | Laravel 10 |
| Database   | MySQL / MariaDB |
| Tampilan   | Blade, Tailwind CSS (CDN), Font Awesome (CDN) |
| Otentikasi | Dua guard session: `pegawai` dan `pelanggan` |

Tampilan memakai Tailwind dan Font Awesome lewat CDN, dan kode QR QRIS digambar di browser dengan pustaka `qrcodejs` dari CDN. Karena itu **tidak perlu `npm install`**, tetapi perangkat harus tersambung internet saat membuka aplikasi.

## 5. Struktur Database

Sembilan tabel aplikasi (hasil migration):

| Tabel                | Keterangan |
| -------------------- | ---------- |
| `kategori`           | Kategori produk |
| `merek`              | Merek dan negara asal |
| `produk`             | Produk, harga, stok, lama garansi (bulan), tanggal masuk, foto |
| `pegawai`            | Pegawai, jabatan, username, password (hash) |
| `pelanggan`          | Pelanggan, email unik untuk login, `username` opsional (diisi pegawai, tidak dipakai login), password (hash) |
| `transaksi_penjualan`| Transaksi, pembayaran, bukti bayar, dan data pengiriman (penerima, alamat, status, kurir, resi, estimasi tiba) |
| `detail_penjualan`   | Produk dalam tiap transaksi: jumlah, harga satuan, subtotal |
| `garansi`            | Garansi per baris detail penjualan (nomor, tanggal mulai dan berakhir, ketentuan) |
| `klaim_garansi`      | Riwayat klaim atas garansi |

Relasi utama:

```
kategori 1──N produk N──1 merek
produk   1──N detail_penjualan N──1 transaksi_penjualan N──1 pelanggan
                                          transaksi_penjualan N──0..1 pegawai
detail_penjualan 1──0..1 garansi 1──N klaim_garansi N──0..1 pegawai
```

Beberapa kolom sengaja menyimpan nilai turunan atau salinan agar riwayat tidak berubah: `total_bayar` (jumlah seluruh subtotal), `subtotal` (jumlah × harga satuan), `harga_satuan`, serta `lama_garansi` dan `tanggal_berakhir` pada garansi.

Tabel bawaan Laravel (`users`, `password_reset_tokens`, `failed_jobs`, `personal_access_tokens`) tidak dipakai oleh fitur aplikasi.

ERD lengkap ada di `docs/ERD_Gadgetkuori.drawio` (lihat [bagian 10](#10-dokumentasi-dan-diagram)).

## 6. Alur Pesanan

```
Pelanggan checkout ──► transaksi "Belum Dibayar" (stok dikurangi, pengiriman "Menunggu")
        │
        ▼
Pelanggan bayar (Transfer Bank / QRIS) + unggah bukti
        │
        ▼
Kasir/Manajer periksa pembayaran
   ├─ valid ──► "Lunas" + garansi terbit + pengiriman "Dikemas"
   │               │
   │               ▼
   │          Kasir/Manajer isi estimasi tiba, kurir, no. resi ──► "Dikirim"
   │               │
   │               ▼
   │          Pelanggan konfirmasi ──► "Diterima"
   │
   └─ batal ──► "Dibatalkan" + stok dikembalikan
```

Pelanggan juga dapat membatalkan sendiri pesanan yang masih berstatus *Belum Dibayar*.

## 7. Cara Menjalankan

### Prasyarat

- PHP 8.1 atau lebih baru (ekstensi umum Laravel, termasuk `pdo_mysql` dan `gd` atau `fileinfo` untuk unggah gambar)
- Composer
- MySQL atau MariaDB (misalnya lewat XAMPP/Laragon)

### Langkah

```bash
# 1. Masuk ke folder aplikasi
cd laravel

# 2. Pasang dependensi PHP
composer install

# 3. Siapkan file lingkungan
cp .env.example .env
php artisan key:generate
```

Buat database kosong (misalnya `project_gadget`), lalu sesuaikan bagian database pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_gadget
DB_USERNAME=root
DB_PASSWORD=
```

Tambahkan tiga baris berikut ke `.env` (belum ada di `.env.example`) lalu isi sesuai toko; penjelasannya ada di [bagian 8](#8-konfigurasi):

```env
NAMA_TOKO="Gadgetkuori"
QRIS_STATIS=
REKENING_TOKO=
```

Isi database dengan migration dan seeder (data awal bersih):

```bash
php artisan migrate --seed
```

Terakhir:

```bash
# 4. Tautan penyimpanan untuk bukti pembayaran (foto produk disajikan lewat rute /media)
php artisan storage:link

# 5. Jalankan server pengembangan
php artisan serve
```

Buka `http://127.0.0.1:8000`.

> Jika `storage:link` menolak karena `public/storage` sudah ada sebagai folder biasa, hapus folder tersebut lalu jalankan ulang perintahnya.

### Akun demo (dari seeder)

Kata sandi awal semua akun: `password123`.

| Peran     | Login | Halaman |
| --------- | ----- | ------- |
| Manajer   | `admin.ahmad` | `/pegawai/login` |
| Kasir     | `budi.santoso`, `dewi.lestari` | `/pegawai/login` |
| Teknisi   | `teknisi.doni` | `/pegawai/login` |
| Gudang    | `gudang.hendra` | `/pegawai/login` |
| Pelanggan | email `andi.pratama@email.com` | `/login` |

Pelanggan baru dapat mendaftar sendiri di `/daftar`. Ganti semua kata sandi awal sebelum aplikasi dipakai di luar lingkungan belajar.

## 8. Konfigurasi

Pengaturan khusus toko ada di `config/` dan `.env`.

| Pengaturan | Lokasi | Fungsi |
| ---------- | ------ | ------ |
| `NAMA_TOKO` | `.env` | Nama toko pada halaman pembayaran (bawaan `Gadgetkuori`) |
| `QRIS_STATIS` | `.env` | Teks QRIS statis toko; dipakai membuat QRIS dinamis. Bila kosong, kode QR tidak ditampilkan |
| `REKENING_TOKO` | `.env` | Informasi rekening untuk pembayaran Transfer Bank |
| `ketentuan` | `config/garansi.php` | Teks ketentuan garansi yang disalin ke setiap garansi baru |
| `maks_klaim` | `config/garansi.php` | Batas jumlah klaim per garansi (`null` = tanpa batas) |

`.env.example` hanya memuat pengaturan bawaan Laravel, sehingga `NAMA_TOKO`, `QRIS_STATIS`, dan `REKENING_TOKO` perlu ditambahkan sendiri. Berkas `.env` berisi `APP_KEY`, data rekening, dan QRIS toko, jadi jangan menyertakannya pada arsip atau repositori.

## 9. Struktur Folder

```
ProjekPenjualanHandphone/
├── README.md
├── ProjekBasdat.drawio       # berkas diagram kosong; ERD yang dipakai ada di docs/
├── docs/                     # dokumentasi, PRD, dan diagram (.drawio, .png)
└── laravel/
    ├── .env.example
    ├── app/
    │   ├── Http/Controllers/
    │   │   ├── Admin/        # Dashboard, Produk, Kategori, Merek, Pegawai,
    │   │   │                 # Pelanggan, Transaksi, Klaim, Laporan
    │   │   ├── Auth/         # Login pegawai, login dan registrasi pelanggan
    │   │   └── ...           # DashboardPelanggan, Katalog, Keranjang, Pesanan, Garansi
    │   ├── Http/Middleware/CekJabatan.php   # pembatasan akses per jabatan
    │   ├── Models/           # Eloquent: 9 model tabel aplikasi (+ User bawaan Laravel)
    │   └── Services/Qris.php # pembuat QRIS dinamis
    ├── config/               # garansi.php, pembayaran.php, auth.php (dua guard)
    ├── database/
    │   ├── migrations/       # skema database
    │   └── seeders/          # data awal
    ├── resources/views/      # Blade: admin/, auth/, garansi/, katalog/, keranjang/,
    │                         #        layouts/, pesanan/, transaksi/
    └── routes/web.php        # seluruh rute aplikasi
```

## 10. Dokumentasi dan Diagram

| Berkas | Isi |
| ------ | --- |
| `Dokumentasi_Gadgetkuori.docx` | Latar belakang, permasalahan, solusi, fitur, dan perancangan sistem |
| `PRD_Sistem_Basis_Data_Toko_Gadget_Online_Revisi.docx` | Product Requirements Document: kebutuhan, struktur tabel, relasi, dan alur proses |
| `ERD_Gadgetkuori.drawio` | ERD sembilan tabel (notasi crow's foot) |
| `UseCase_Pembeli.drawio`, `UseCase_Pegawai.drawio` | Use case sisi pembeli dan sisi pegawai |
| `Class_Gadgetkuori.drawio` | Class diagram model |
| `Activity_Pembelian_Pembayaran.drawio`, `Activity_Pengiriman.drawio`, `Activity_Klaim_Garansi.drawio` | Activity diagram pembelian, pengiriman, dan klaim garansi |
| `Sequence_Checkout.drawio` | Sequence diagram checkout, konfirmasi lunas, dan pengiriman |

Letakkan berkas-berkas di atas pada folder `docs/`. Berkas `ProjekBasdat.drawio` di root proyek hanya berisi kanvas kosong dan dapat dihapus. Berkas `.drawio` dapat dibuka dan diedit di [draw.io](https://app.diagrams.net); gambar PNG hasil ekspornya disertakan untuk dilihat langsung.

## 11. Batasan Sistem

Hal-hal berikut **tidak** termasuk dalam aplikasi:

- Pembelian barang dari supplier dan pencatatan stok masuk.
- Integrasi payment gateway; pembayaran diperiksa dan dikonfirmasi manual oleh pegawai berdasarkan bukti.
- Integrasi API kurir dan pelacakan otomatis; kurir dan nomor resi diisi manual oleh pegawai.
- Pengembalian (retur) barang.
- Aplikasi mobile.
- Promo, voucher, atau diskon.
- Notifikasi otomatis lewat email atau pesan singkat.
- Penjualan langsung di toko: semua transaksi berasal dari checkout web, sedangkan Kasir dan Manajer hanya memproses transaksi yang sudah dibuat pelanggan.
- Pengubahan profil atau kata sandi oleh pelanggan sendiri (hanya lewat Manajer atau Kasir) dan pemulihan kata sandi lewat email.
- Penyimpanan keranjang di akun: keranjang hanya hidup di session browser.
