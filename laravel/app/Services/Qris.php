<?php

namespace App\Services;

use InvalidArgumentException;

class Qris
{
    /**
     * Ubah teks QRIS statis toko menjadi QRIS dinamis dengan nominal tertentu.
     * Hasilnya berupa teks yang dijadikan kode QR; aplikasi bank/e-wallet
     * akan menampilkan nominalnya otomatis.
     */
    public static function dinamis(string $statis, int $nominal): string
    {
        $hasil = [];
        $adaNegara = false;

        foreach (self::urai(trim($statis)) as [$tag, $nilai]) {
            if ($tag === '63' || $tag === '54') {
                continue; // CRC lama dan nominal lama dibuang
            }
            if ($tag === '01') {
                $nilai = '12'; // 11 = statis, 12 = dinamis
            }
            if ($tag === '58') {
                $adaNegara = true;
                $hasil[] = ['54', (string) $nominal]; // nominal ditaruh sebelum kode negara
            }
            $hasil[] = [$tag, $nilai];
        }

        if (! $adaNegara) {
            throw new InvalidArgumentException('Format QRIS tidak valid: kode negara (tag 58) tidak ditemukan.');
        }

        $payload = '';
        foreach ($hasil as [$tag, $nilai]) {
            $payload .= $tag . str_pad((string) strlen($nilai), 2, '0', STR_PAD_LEFT) . $nilai;
        }
        $payload .= '6304';

        return $payload . self::crc16($payload);
    }

    /** Pecah teks QRIS (format TLV) menjadi daftar [tag, nilai]. */
    private static function urai(string $teks): array
    {
        $hasil = [];
        $i = 0;
        $n = strlen($teks);

        while ($i + 4 <= $n) {
            $tag = substr($teks, $i, 2);
            $panjang = (int) substr($teks, $i + 2, 2);
            $nilai = substr($teks, $i + 4, $panjang);

            if (strlen($nilai) !== $panjang) {
                throw new InvalidArgumentException('Format QRIS tidak valid.');
            }

            $hasil[] = [$tag, $nilai];
            $i += 4 + $panjang;
        }

        if (empty($hasil)) {
            throw new InvalidArgumentException('Teks QRIS kosong.');
        }

        return $hasil;
    }

    /** CRC16-CCITT (poly 0x1021, init 0xFFFF) sesuai standar QRIS. */
    private static function crc16(string $data): string
    {
        $crc = 0xFFFF;

        for ($i = 0, $n = strlen($data); $i < $n; $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($b = 0; $b < 8; $b++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}