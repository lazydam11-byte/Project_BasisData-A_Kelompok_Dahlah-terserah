<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Produk;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori
        $kategori = collect(['Smartphone', 'Laptop', 'Tablet', 'Aksesori', 'Smartwatch'])
            ->mapWithKeys(fn ($n) => [$n => Kategori::create(['nama_kategori' => $n])->id_kategori]);

        // Merek
        $merek = collect([
            ['Apple', 'Amerika Serikat'], ['Samsung', 'Korea Selatan'], ['Xiaomi', 'Tiongkok'],
            ['Sony', 'Jepang'], ['OPPO', 'Tiongkok'],
        ])->mapWithKeys(fn ($m) => [$m[0] => Merek::create(['nama_merek' => $m[0], 'negara_asal' => $m[1]])->id_merek]);

        // Produk: nama, kategori, merek, harga, stok, garansi (bulan), tanggal masuk
        $daftarProduk = [
            ['iPhone 15 Pro 256GB', 'Smartphone', 'Apple', 14500000, 25, 12, '2026-09-05'],
            ['Samsung Galaxy S24 Ultra', 'Smartphone', 'Samsung', 19000000, 12, 12, '2026-09-12'],
            ['MacBook Pro M3 14"', 'Laptop', 'Apple', 28000000, 0, 12, '2026-09-20'],
            ['Xiaomi 14T Pro 12/512GB', 'Smartphone', 'Xiaomi', 9499000, 20, 6, '2026-10-02'],
            ['iPad Air 11" M2 WiFi 128GB', 'Tablet', 'Apple', 9499000, 2, 12, '2026-10-15'],
            ['OPPO Reno12 5G 12/256GB', 'Smartphone', 'OPPO', 6999000, 8, 12, '2026-10-18'],
            ['Sony WF-1000XM5', 'Aksesori', 'Sony', 3499000, 6, 12, '2026-10-20'],
            ['Xiaomi Watch S3', 'Smartwatch', 'Xiaomi', 1799000, 3, 12, '2026-10-22'],
        ];
        foreach ($daftarProduk as [$nama, $kat, $mrk, $harga, $stok, $garansi, $tgl]) {
            Produk::create([
                'nama_produk' => $nama,
                'id_kategori' => $kategori[$kat],
                'id_merek' => $merek[$mrk],
                'harga' => $harga,
                'stok' => $stok,
                'lama_garansi' => $garansi,
                'tanggal_masuk' => $tgl,
            ]);
        }

        // Pegawai (password awal: password123, otomatis di-hash oleh model)
        foreach ([
            ['Ahmad Fauzi', 'Manajer', 'admin.ahmad'],
            ['Budi Santoso', 'Kasir', 'budi.santoso'],
            ['Doni Prasetyo', 'Teknisi', 'teknisi.doni'],
            ['Dewi Lestari', 'Kasir', 'dewi.lestari'],
            ['Hendra Wijaya', 'Gudang', 'gudang.hendra'],
        ] as [$nama, $jabatan, $username]) {
            Pegawai::create(['nama' => $nama, 'jabatan' => $jabatan, 'username' => $username, 'password' => 'password123']);
        }

        // Pelanggan tanpa akun (dicatat pegawai di toko)
        foreach ([
            ['Andi Wijaya', 'Jl. Merdeka No. 12, Jakarta', '0812-3456-7890', 'andi.wijaya@email.com'],
            ['Sari Dewi', 'Jl. Sudirman No. 45, Bandung', '0856-7890-1234', 'sari.dewi@email.com'],
            ['Agus Hermawan', 'Jl. Pemuda No.8, Bandung', '0822-9876-5432', 'agus.h@yahoo.co.id'],
        ] as [$nama, $alamat, $telp, $email]) {
            Pelanggan::create(['nama' => $nama, 'alamat' => $alamat, 'no_telepon' => $telp, 'email' => $email]);
        }

        // Pelanggan dengan akun login (password awal: password123)
        Pelanggan::create([
            'nama' => 'Andi Pratama',
            'alamat' => 'Jl. Contoh No. 5, Depok',
            'no_telepon' => '0813-1111-2222',
            'email' => 'andi.pratama@email.com',
            'username' => 'andi.pratama',
            'password' => 'password123',
        ]);
    }
}

