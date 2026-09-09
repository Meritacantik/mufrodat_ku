<?php

namespace App\Services;

class PangkatService
{
    protected const TINGKATAN = [
        ['min' => 0,   'nama' => 'Pemula',      'icon' => 'sparkles'],
        ['min' => 1,   'nama' => 'Pembelajar',  'icon' => 'book'],
        ['min' => 50,  'nama' => 'Mahir',       'icon' => 'target'],
        ['min' => 100, 'nama' => 'Master',      'icon' => 'trophy'],
    ];

    public function hitung(int $persenSelesai, string $kelas): array
    {
        $sekarang = self::TINGKATAN[0];
        $berikutnya = null;

        foreach (self::TINGKATAN as $i => $tingkat) {
            if ($persenSelesai >= $tingkat['min']) {
                $sekarang = $tingkat;
                $berikutnya = self::TINGKATAN[$i + 1] ?? null;
            }
        }

        $nama = $sekarang['nama'] === 'Master' ? "Master Kelas {$kelas}" : $sekarang['nama'];

        return [
            'nama'            => $nama,
            'icon'            => $sekarang['icon'],
            'persen_selesai'  => $persenSelesai,
            'pangkat_berikutnya' => $berikutnya['nama'] ?? null,
            'sisa_persen'     => $berikutnya ? max(0, $berikutnya['min'] - $persenSelesai) : 0,
            'sudah_maksimal'  => $berikutnya === null,
        ];
    }
}
