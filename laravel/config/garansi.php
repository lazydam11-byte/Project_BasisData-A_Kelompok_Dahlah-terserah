<?php

return [
    // Ketentuan garansi yang disalin ke setiap garansi baru.
    // Ganti dengan kebijakan garansi toko yang sebenarnya.
    'ketentuan' => 'Garansi berlaku untuk kerusakan akibat cacat produksi selama masa garansi. '
        . 'Garansi tidak berlaku jika unit rusak karena benturan, terkena cairan, atau dibongkar pihak lain. '
        . 'Bawa unit beserta nomor garansi ke toko untuk pemeriksaan.',
    
            // Batas jumlah klaim per garansi (klaim yang ditolak tidak dihitung). null = tanpa batas.
    'maks_klaim' => 1,
];