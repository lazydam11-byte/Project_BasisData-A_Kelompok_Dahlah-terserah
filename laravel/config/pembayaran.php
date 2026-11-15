<?php

return [
    'nama_toko' => env('NAMA_TOKO', 'Gadgetkuori'),

    // Teks QRIS statis toko, diisi di file .env
    'qris_statis' => env('QRIS_STATIS'),

    // Informasi rekening untuk Transfer Bank, diisi di file .env
    'rekening' => env('REKENING_TOKO'),
];