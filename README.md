# PROJECT BASIS DATA
## By Kelompok Dahlah terserah
### Judul: Sistem Basis Data Toko Gadget Online

| No | Nama                       | NPM        |
| -- | -------------------------- | ---------- |
| 1  | Harisa Malfa Liya          | 4525210027 |
| 2  | Karel Abimanyu Ahpandi     | 4525210031 |
| 3  | Khairul Adam Efendi        | 4525210035 |
| 4  | Larasati Dewi              | 4525210037 |
| 5  | Muhammad Haikal Azhari     | 4525210046 |
| 6  | Adelita Daeli              | 4525210049 |
   
### Link ERD

[ERD](https://drive.google.com/file/d/13yxlr54ZRnxv6GOJQ8zMxTt3fpfw-ynG/view?usp=sharing)

---

## 1. Latar Belakang
Toko gadget tidak luput dari perkembangan teknologi yang memungkinkan kegiatan jual beli dilakukan secara online. Dalam menjalankan toko gadget online, pengelolaan data tidak dapat dilakukan secara sembarangan, melainkan memerlukan sistem basis data yang terstruktur dan rapi agar data produk, pelanggan, stok barang, transaksi, dan pengiriman dapat dikelola dengan baik serta menghindari kesalahan pencatatan.

Oleh karena itu, sistem basis data ini dibuat untuk memudahkan toko gadget online dalam menyimpan, mengelola, dan mencari data secara efisien. Selain itu, sistem ini diharapkan dapat membantu karyawan dalam mengelola transaksi dan data produk, serta membantu manajer dalam memantau aktivitas penjualan dan memperoleh laporan yang dibutuhkan untuk mendukung pengambilan keputusan.

## 2. Tujuan
Tujuan dari sistem basis data Toko Gadget Online, yaitu:
* Mengelola data produk.
* Mengelola data pelanggan.
* Mengelola data pegawai.
* Mencatat transaksi penjualan.
* Mencatat garansi dari produk.
* Membantu menghasilkan laporan penjualan. 

## 3. Deskripsi Sistem

Sistem ini merupakan basis data untuk toko yang menjual produk bermerek dengan layanan garansi dan tukar tambah. Data dibagi menjadi beberapa tabel yang saling berhubungan.

Tabel yang digunakan dalam sistem ini meliputi:

1. Kategori
2. Merek
3. Produk
4. Pegawai
5. Pelanggan
6. Transaksi Penjualan
7. Detail Penjualan
8. Garansi
9. Klaim Garansi
10. Tukar Tambah

Tabel Transaksi Penjualan menyimpan informasi transaksi, sedangkan Detail Penjualan menyimpan daftar produk dalam setiap transaksi. Tabel Garansi menyimpan informasi garansi produk yang terjual, dan Klaim Garansi menyimpan riwayat klaim atas garansi tersebut. Sementara itu, tabel Tukar Tambah menyimpan informasi barang lama yang ditukarkan pelanggan dalam suatu transaksi.

## 4. Kebutuhan Pengguna

| Pengguna | Kebutuhan |
|---|---|
| Pegawai | Mencatat transaksi penjualan, data pelanggan, garansi, klaim garansi, dan tukar tambah. |
| Pengelola Toko | Mengelola data produk, kategori, merek, pegawai, dan memantau stok. |
| Pemilik Toko | Melihat informasi dan laporan penjualan, garansi, serta klaim garansi. |

## 5. User Story

Beberapa kebutuhan pengguna dalam sistem ini adalah:

- Sebagai pegawai, saya ingin mencatat transaksi penjualan agar data penjualan tersimpan.
- Sebagai pegawai, saya ingin mencatat produk yang dibeli pelanggan dalam satu transaksi.
- Sebagai pegawai, saya ingin mencatat garansi produk yang terjual agar masa berlakunya jelas.
- Sebagai pegawai, saya ingin mencatat klaim garansi dan memperbarui statusnya sampai selesai.
- Sebagai pegawai, saya ingin mencatat barang lama dan nilai tukarnya saat pelanggan melakukan tukar tambah.
- Sebagai pengelola toko, saya ingin menyimpan data produk, kategori, dan merek agar stok serta harga dapat dikelola.
- Sebagai pengelola toko, saya ingin menyimpan data pelanggan dan pegawai.
- Sebagai pemilik toko, saya ingin melihat data penjualan, garansi, dan klaim untuk mengetahui kondisi usaha.

## 6. Ruang Lingkup

### 6.1 Yang Termasuk dalam Sistem

- Data kategori dan merek.
- Data produk, harga, dan stok.
- Data pelanggan.
- Data pegawai.
- Data transaksi penjualan dan detail penjualan.
- Data garansi dan klaim garansi.
- Data tukar tambah.
- Relasi antar tabel.

### 6.2 Yang Tidak Termasuk dalam Sistem

- Pembelian barang dari supplier dan pencatatan stok masuk.
- Pembayaran online.
- Aplikasi mobile.
- Pengiriman barang.
- Sistem promo atau voucher otomatis. Potongan harga hanya dicatat per item pada Detail Penjualan.
- Penjualan kembali barang bekas hasil tukar tambah.

## 7. Persyaratan Fungsional

| Kode | Persyaratan |
|---|---|
| FR-01 | Sistem dapat menyimpan data kategori dan merek produk. |
| FR-02 | Sistem dapat menyimpan data produk, termasuk harga dan stok. |
| FR-03 | Sistem dapat menyimpan data pelanggan. |
| FR-04 | Sistem dapat menyimpan data pegawai, termasuk username dan password untuk akses sistem. |
| FR-05 | Sistem dapat mencatat transaksi penjualan beserta tanggal, pelanggan, pegawai, metode pembayaran, dan status pembayaran. |
| FR-06 | Sistem dapat mencatat detail produk dalam setiap transaksi. |
| FR-07 | Sistem dapat menyimpan jumlah, harga satuan saat transaksi, dan diskon produk yang terjual. |
| FR-08 | Sistem dapat menghitung subtotal detail penjualan berdasarkan jumlah dikalikan harga satuan, kemudian dikurangi diskon. |
| FR-09 | Sistem dapat menghitung dan menyimpan total pembayaran transaksi dari jumlah seluruh subtotal. |
| FR-10 | Sistem dapat mencatat garansi dari detail penjualan, meliputi nomor garansi, tanggal mulai, lama garansi, dan ketentuan garansi. |
| FR-11 | Sistem dapat menghitung tanggal berakhir garansi berdasarkan tanggal mulai dan lama garansi. |
| FR-12 | Sistem dapat mencatat klaim garansi beserta tanggal klaim, keluhan, kondisi barang, dan pegawai yang menangani. |
| FR-13 | Sistem dapat mencatat status klaim, tanggal selesai, dan keterangan penanganan klaim. |
| FR-14 | Sistem dapat mencatat tukar tambah pada suatu transaksi, meliputi nama barang lama, jenis, kondisi, nilai tukar, pegawai yang memproses, dan status tukar tambah. |
| FR-15 | Sistem dapat menghitung tambahan pembayaran tukar tambah berdasarkan total pembayaran dikurangi nilai tukar. |
| FR-16 | Sistem dapat menyediakan data untuk laporan penjualan. |

## 8. Struktur Tabel Database

### 8.1 Tabel Kategori

| Atribut | Keterangan |
|---|---|
| `id_kategori` | Primary Key |
| `nama_kategori` | Nama kategori produk |

### 8.2 Tabel Merek

| Atribut | Keterangan |
|---|---|
| `id_merek` | Primary Key |
| `nama_merek` | Nama merek |
| `negara_asal` | Negara asal merek |

### 8.3 Tabel Produk

| Atribut | Keterangan |
|---|---|
| `id_produk` | Primary Key |
| `nama_produk` | Nama produk |
| `harga` | Harga produk |
| `stok` | Jumlah stok produk |
| `id_merek` | Foreign Key ke tabel Merek |
| `id_kategori` | Foreign Key ke tabel Kategori |

### 8.4 Tabel Pegawai

| Atribut | Keterangan |
|---|---|
| `id_pegawai` | Primary Key |
| `nama` | Nama pegawai |
| `jabatan` | Jabatan pegawai |
| `username` | Username untuk akses sistem |
| `password` | Password pegawai yang disimpan dalam bentuk hash |

### 8.5 Tabel Pelanggan

| Atribut | Keterangan |
|---|---|
| `id_pelanggan` | Primary Key |
| `nama` | Nama pelanggan |
| `alamat` | Alamat pelanggan |
| `no_telepon` | Nomor telepon pelanggan |
| `email` | Email pelanggan |
| `password` | Password pelanggan |

### 8.6 Tabel Transaksi Penjualan

| Atribut | Keterangan |
|---|---|
| `id_transaksi` | Primary Key |
| `tanggal` | Tanggal transaksi |
| `metode_pembayaran` | Metode pembayaran |
| `status_pembayaran` | Status pembayaran |
| `total_bayar` | Atribut turunan dari seluruh subtotal |
| `id_pelanggan` | Foreign Key ke tabel Pelanggan |
| `id_pegawai` | Foreign Key ke tabel Pegawai |

### 8.7 Tabel Detail Penjualan

| Atribut | Keterangan |
|---|---|
| `id_detail` | Primary Key |
| `id_transaksi` | Foreign Key ke tabel Transaksi Penjualan |
| `id_produk` | Foreign Key ke tabel Produk |
| `jumlah` | Jumlah produk yang dibeli |
| `harga_satuan` | Harga produk saat transaksi |
| `diskon` | Potongan harga produk |
| `sub_total` | Atribut turunan dari jumlah, harga satuan, dan diskon |

### 8.8 Tabel Garansi

| Atribut | Keterangan |
|---|---|
| `id_garansi` | Primary Key |
| `id_detail` | Foreign Key ke tabel Detail Penjualan |
| `nomor_garansi` | Nomor garansi produk |
| `tanggal_mulai` | Tanggal mulai garansi |
| `lama_garansi` | Durasi garansi |
| `tanggal_berakhir` | Atribut turunan dari tanggal mulai dan lama garansi |
| `ketentuan_garansi` | Ketentuan garansi produk |

### 8.9 Tabel Klaim Garansi

| Atribut | Keterangan |
|---|---|
| `id_klaim` | Primary Key |
| `id_garansi` | Foreign Key ke tabel Garansi |
| `id_pegawai` | Foreign Key ke tabel Pegawai |
| `tanggal_klaim` | Tanggal klaim diajukan |
| `tanggal_selesai` | Tanggal klaim selesai ditangani |
| `keluhan` | Keluhan pelanggan |
| `kondisi_barang` | Kondisi barang saat klaim |
| `status_klaim` | Status penanganan klaim |
| `keterangan` | Keterangan penanganan klaim |

### 8.10 Tabel Tukar Tambah

| Atribut | Keterangan |
|---|---|
| `id_tukar_tambah` | Primary Key |
| `id_transaksi` | Foreign Key ke tabel Transaksi Penjualan |
| `id_pegawai` | Foreign Key ke tabel Pegawai |
| `tanggal` | Tanggal tukar tambah |
| `nama_barang_lama` | Nama barang yang ditukarkan |
| `jenis_barang_lama` | Jenis barang lama |
| `kondisi_barang` | Kondisi barang lama |
| `nilai_tukar` | Nilai barang lama yang disepakati |
| `tambahan_bayar` | Atribut turunan dari total pembayaran dikurangi nilai tukar |
| `status_tukar_tambah` | Status tukar tambah |

## 9. Relasi Antar Tabel

| Relasi | Kardinalitas | Keterangan |
|---|---|---|
| Kategori – Produk | 1:N | Satu kategori dapat mengelompokkan banyak produk. |
| Merek – Produk | 1:N | Satu merek dapat memiliki banyak produk. |
| Pelanggan – Transaksi Penjualan | 1:N | Satu pelanggan dapat melakukan banyak transaksi. |
| Pegawai – Transaksi Penjualan | 1:N | Satu pegawai dapat melayani banyak transaksi. |
| Transaksi Penjualan – Detail Penjualan | 1:N | Satu transaksi dapat memiliki banyak detail penjualan. |
| Produk – Detail Penjualan | 1:N | Satu produk dapat tercatat dalam banyak detail penjualan. |
| Detail Penjualan – Garansi | 1:0..1 | Satu detail penjualan dapat memperoleh paling banyak satu garansi. |
| Garansi – Klaim Garansi | 1:N | Satu garansi dapat memiliki banyak riwayat klaim. |
| Pegawai – Klaim Garansi | 1:N | Satu pegawai dapat menangani banyak klaim. |
| Transaksi Penjualan – Tukar Tambah | 1:N | Satu transaksi dapat terkait dengan tukar tambah. |
| Pegawai – Tukar Tambah | 1:N | Satu pegawai dapat memproses banyak tukar tambah. |

Produk yang dibeli dalam satu transaksi disimpan pada tabel Detail Penjualan karena satu transaksi dapat memiliki beberapa produk. Pelanggan tidak dihubungkan langsung dengan Klaim Garansi dan Tukar Tambah. Data pelanggan dapat ditelusuri melalui Transaksi Penjualan agar tidak terjadi pengulangan data.

## 10. Alur Proses Sistem

### 10.1 Proses Penjualan

1. Pelanggan datang ke toko.
2. Pegawai melayani pelanggan.
3. Pegawai mencatat transaksi penjualan.
4. Produk yang dibeli dicatat pada tabel Detail Penjualan.
5. Data produk digunakan dalam pencatatan transaksi.
6. Garansi dicatat apabila produk memiliki garansi.
7. Total pembayaran dihitung dari seluruh subtotal.

### 10.2 Proses Tukar Tambah

1. Pelanggan menyerahkan barang lama.
2. Pegawai memeriksa kondisi barang.
3. Nilai tukar barang ditentukan.
4. Data barang lama dan nilai tukar dicatat pada tabel Tukar Tambah.
5. Data tukar tambah dihubungkan dengan transaksi penjualan.
6. Tambahan pembayaran dihitung dari total pembayaran dikurangi nilai tukar.

### 10.3 Proses Klaim Garansi

1. Pelanggan membawa barang yang mengalami kerusakan.
2. Pegawai memeriksa informasi garansi.
3. Klaim garansi dicatat beserta keluhan dan kondisi barang.
4. Status klaim diperbarui selama proses penanganan.
5. Tanggal selesai dan keterangan penanganan dicatat setelah klaim selesai.

## 11. Persyaratan Non-Fungsional

Sistem diharapkan memenuhi beberapa persyaratan berikut:

- Data tersimpan dengan rapi dan tidak ganda.
- Hubungan antar tabel jelas.
- Harga satuan disimpan pada Detail Penjualan agar perubahan harga produk tidak mengubah riwayat transaksi.
- Nilai atribut turunan konsisten dengan data sumbernya.
- Password pegawai disimpan dalam bentuk hash, bukan teks biasa.
- Data dapat digunakan untuk membuat laporan penjualan.
- Data yang disimpan harus sesuai dengan struktur tabel serta aturan Primary Key dan Foreign Key.

## 12. Technical Design

ERD menggunakan notasi Chen dengan ketentuan berikut:

- Garis bawah menandai Primary Key.
- Elips putus-putus menandai atribut turunan.
- Label `1`, `N`, dan `0..1` menunjukkan kardinalitas.
- Foreign Key tidak digambar langsung pada ERD karena sudah diwakili oleh relasi, tetapi dicantumkan dalam struktur tabel.

Struktur utama terdiri dari sepuluh tabel, yaitu Kategori, Merek, Produk, Pegawai, Pelanggan, Transaksi Penjualan, Detail Penjualan, Garansi, Klaim Garansi, dan Tukar Tambah. Tabel-tabel tersebut saling terhubung melalui Primary Key dan Foreign Key.

## 13. Jadwal dan Timeline

| Tahap | Kegiatan |
|---|---|
| 1 | Menentukan kebutuhan sistem |
| 2 | Menentukan tabel dan atribut |
| 3 | Membuat ERD |
| 4 | Mereview dan merapikan ERD |
| 5 | Membuat model relasional dan diagram skema |
| 6 | Membuat PRD |
| 7 | Menyelesaikan dan mengumpulkan tugas |

## 14. Risiko dan Asumsi

### 14.1 Risiko

- Kesalahan dalam menentukan relasi antar tabel.
- Kesalahan dalam menentukan Primary Key dan Foreign Key.
- Data yang dimasukkan tidak sesuai dengan struktur tabel.
- Nilai atribut turunan, seperti `total_bayar`, `sub_total`, `tanggal_berakhir`, dan `tambahan_bayar`, tidak sinkron dengan data sumbernya.
- Pencatatan tukar tambah tidak konsisten dengan total pembayaran transaksi.

### 14.2 Asumsi

- Data yang digunakan sesuai dengan kebutuhan toko.
- Setiap transaksi memiliki `id_transaksi`.
- Satu transaksi dapat memiliki lebih dari satu produk.
- Garansi dicatat per baris Detail Penjualan, dan satu baris detail memperoleh paling banyak satu garansi.
- `total_bayar` dihitung dari jumlah subtotal sebelum dikurangi nilai tukar.
- `tambahan_bayar` dihitung dari `total_bayar` dikurangi `nilai_tukar`.
- Tukar tambah selalu terkait dengan suatu transaksi, dan pelanggannya mengikuti pelanggan pada transaksi tersebut.
- Barang lama hasil tukar tambah hanya dicatat sebagai data tukar tambah dan tidak masuk ke stok produk.
- Data pelanggan dan pegawai sudah tersedia saat transaksi dilakukan.

## 15. Kriteria Keberhasilan

Sistem dianggap sesuai apabila:

- Semua tabel yang dibutuhkan sudah dibuat.
- Primary Key dan Foreign Key sudah ditentukan.
- Relasi antar tabel sesuai dengan kebutuhan sistem.
- Data transaksi dan detail produk dalam transaksi dapat disimpan.
- Garansi, klaim garansi, dan tukar tambah dapat dicatat serta ditelusuri dari transaksi asalnya.
- Struktur database sesuai dengan model relasional dan ERD.

## 16. Persetujuan Pemangku Kepentingan

PRD ini dibuat sebagai rancangan kebutuhan untuk sistem basis data penjualan, garansi, dan tukar tambah, serta digunakan sebagai dasar dalam proses perancangan database.

## 17. Kesimpulan

Sistem basis data ini dibuat untuk membantu mengelola data produk, pelanggan, pegawai, transaksi penjualan, garansi, klaim garansi, dan tukar tambah. Dengan hubungan antar tabel yang jelas, data transaksi dapat disimpan secara lebih terstruktur, riwayat garansi dan klaim dapat ditelusuri, serta data dapat digunakan untuk kebutuhan pengelolaan dan laporan penjualan.
