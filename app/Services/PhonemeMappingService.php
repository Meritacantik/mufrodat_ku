<?php

namespace App\Services;

class PhonemeMappingService
{
    protected array $normalisasi;
    protected array $keHijaiyah;
    protected array $harakat;

    public function __construct()
    {
        $config = config('phonemes');
        $this->normalisasi = $config['normalisasi'];
        $this->keHijaiyah  = $config['ke_hijaiyah'];
        $this->harakat     = $config['harakat'];
    }

    public function normalisasi(string $teks): string
    {
        $teks = strtolower(trim($teks));

        do {
            $sebelum = $teks;
            foreach ($this->normalisasi as $baku => $variasiList) {
                foreach ($variasiList as $variasi) {
                    $teks = str_replace($variasi, $baku, $teks);
                }
            }
        } while ($teks !== $sebelum);

        return $teks;
    }

    /**
     * Konversi teks Latin (yang sudah dinormalisasi) menjadi rangkaian karakter Hijaiyah,
     * menggunakan tabel pemetaan fonem.
     * Token dicocokkan secara greedy dari yang terpanjang ke terpendek.
     */
    public function keHijaiyah(string $teksLatin): string
    {

        $teks = preg_replace('/[^a-z\']/', '', $teksLatin);

        // Urutkan token berdasarkan panjang (terpanjang dulu) agar "sy" tidak
        // terpecah menjadi "s" + "y".
        $tokens = array_keys($this->keHijaiyah);
        usort($tokens, fn($a, $b) => strlen($b) - strlen($a));

        $hasil = '';
        $i = 0;
        $len = strlen($teks);

        while ($i < $len) {
            $cocok = false;
            foreach ($tokens as $token) {
                $tokenLen = strlen($token);
                if (substr($teks, $i, $tokenLen) === $token) {
                    $hasil .= $this->keHijaiyah[$token];
                    $i += $tokenLen;
                    $cocok = true;
                    break;
                }
            }
            if (!$cocok) {
                // Karakter tidak dikenali (mis. spasi/angka), lewati saja
                $i++;
            }
        }

        return $hasil;
    }

    /**
     * Bersihkan harakat/tanda diakritik dari teks Arab yang tersimpan di database
     * supaya sebanding dengan hasil konversi Latin -> Hijaiyah (yang tanpa harakat).
     */
    public function bersihkanHarakat(string $teksArab): string
    {
        return str_replace($this->harakat, '', trim($teksArab));
    }
}
