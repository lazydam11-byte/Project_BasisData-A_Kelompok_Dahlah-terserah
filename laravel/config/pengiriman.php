<?php

/*
 * Tarif ongkos kirim per kurir (flat per pesanan) dan estimasi tiba.
 * Ubah angka di sini bila tarif berubah; checkout akan memakainya otomatis.
 */
return [
    'kurir' => [
        'JNE Reguler'   => ['tarif' => 20000, 'estimasi' => '2-4 hari'],
        'J&T Express'   => ['tarif' => 18000, 'estimasi' => '2-4 hari'],
        'SiCepat Reg'   => ['tarif' => 17000, 'estimasi' => '2-3 hari'],
        'Gojek Instant' => ['tarif' => 35000, 'estimasi' => 'hari yang sama'],
    ],
];
