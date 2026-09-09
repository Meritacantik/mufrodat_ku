<?php

namespace App\Services;

class LevenshteinService
{
    protected PhonemeMappingService $phoneme;

    public function __construct(PhonemeMappingService $phoneme)
    {
        $this->phoneme = $phoneme;
    }

    // Hitung jarak Levenshtein antara dua string
    public function hitungJarak(string $a, string $b): int
    {
        $a = strtolower(trim($a));
        $b = strtolower(trim($b));

        $m = strlen($a);
        $n = strlen($b);

        $d = [];
        for ($i = 0; $i <= $m; $i++) $d[$i][0] = $i;
        for ($j = 0; $j <= $n; $j++) $d[0][$j] = $j;

        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                if ($a[$i - 1] === $b[$j - 1]) {
                    $d[$i][$j] = $d[$i - 1][$j - 1];
                } else {
                    $d[$i][$j] = 1 + min(
                        $d[$i - 1][$j],     // hapus
                        $d[$i][$j - 1],     // sisip
                        $d[$i - 1][$j - 1]  // ganti
                    );
                }
            }
        }

        return $d[$m][$n];
    }

    /**
     * Threshold adaptif berdasarkan PANJANG KATA INPUT siswa,
     * sesuai skema 4 tingkat pada Tabel 4.7 (Bab IV):
     *  - panjang <= 3 karakter   -> threshold 1
     *  - panjang 4-7 karakter    -> threshold 2
     *  - panjang 8-14 karakter   -> threshold 3
     *  - panjang > 14 karakter   -> threshold 4
     */
    public function getThreshold(string $kata): int
    {
        $panjang = strlen($kata);

        if ($panjang <= 3) {
            return 1;
        }

        if ($panjang <= 7) {
            return 2;
        }

        if ($panjang <= 14) {
            return 3;
        }

        return 4;
    }

    /**
     * Preprocessing teks: (1) Case Folding, (2) Filtering, (3) Normalisasi
     * (Bab II - 2.6 Preprocessing Data Teks).
     */
    public function preprocessing(string $teks): string
    {
        $teks = strtolower(trim($teks));

        // Lepas tanda diakritik vokal panjang (ā->a, ī->i, ū->u, dst.) supaya
        // kata referensi berdiakritik tetap sebanding dengan input siswa yang
        // lazim diketik tanpa diakritik.
        //
        // PENTING: huruf konsonan tebal/emfatik (ṣ, ḍ, ṭ, ẓ) TIDAK disamakan
        // dengan pasangan hurufnya yang polos (s, d, t, z) -- keduanya adalah
        // HURUF BERBEDA dalam Bahasa Arab (mis. ص/Shad vs س/Sin, ض/Dhad vs
        // د/Dal), bukan sekadar variasi ejaan huruf yang sama. Kalau ṣ->s dan
        // ḍ->d, maka "ṣāra" (menjadi) dan "sāra" (berjalan) akan dianggap
        // identik walau artinya beda. Karena itu, huruf emfatik dipetakan ke
        // bentuk baku sesuai tabel normalisasi fonem (Bab II): Shad -> "sh",
        // Dhad -> "dh" -- tetap berbeda dari Sin ("s") dan Dal ("d").
        $petaDiakritik = [
            'ā' => 'a', 'ī' => 'i', 'ū' => 'u', 'ē' => 'e', 'ō' => 'o',
            'ṣ' => 'sh', 'ḍ' => 'dh', 'ḥ' => 'h', 'ẓ' => 'zh', 'ṭ' => 'th',
            'ġ' => 'gh', 'ʿ' => "'", 'ʾ' => "'",
        ];
        $teks = strtr($teks, $petaDiakritik);

        $teks = preg_replace('/[^a-z0-9\s\']/', '', $teks);

        // Tanda petik/apostrof untuk 'ain (ع) dan hamzah (ء) bersifat OPSIONAL
        // (Bab II - normalisasi fonem): "ain" dan "'ain" harus dianggap setara.
        // Karena itu, apostrof dihapus total dari perbandingan (bukan sekadar
        // diseragamkan bentuknya), supaya siswa yang menulis dengan atau tanpa
        // apostrof sama-sama dinilai identik dengan kata referensi.
        $teks = str_replace("'", '', $teks);

        // Spasi antar kata pada frasa (misal "sabahal yaum") bersifat opsional --
        // siswa yang menulis tanpa spasi ("sabahalyaum") harus dinilai identik
        // dengan yang memakai spasi, konsisten dengan tanda hubung pada kata
        // majemuk ("al-madi") yang juga sudah hilang lewat filter di atas dan
        // otomatis membuat kata tersambung tanpa spasi.
        $teks = str_replace(' ', '', $teks);

        $teks = preg_replace('/\s+/', ' ', $teks);
        $teks = trim($teks);
        $teks = $this->phoneme->normalisasi($teks);
        return $teks;
    }

    // Tentukan status jawaban. $kataInput = kata yang DIKETIK SISWA
    // (bukan kata referensi di database)"panjang kata input".
    public function tentukanStatus(int $jarak, string $kataInput): string
    {
        if ($jarak === 0) return 'BENAR';
        if ($jarak <= $this->getThreshold($kataInput)) return 'TYPO';
        return 'SALAH';
    }

    // Hitung poin berdasarkan status
    public function hitungPoin(string $status): int
    {
        return match ($status) {
            'BENAR' => 10,
            'TYPO'  => 5,
            default => 0,
        };
    }

    public function bandingkan(string $jawaban, string $referensiLatin, string $referensiArab = ''): array
    {
        $jawabanBersih   = $this->preprocessing($jawaban);
        $referensiBersih = $this->preprocessing($referensiLatin);

        $jarak  = $this->hitungJarak($jawabanBersih, $referensiBersih);
        $status = $this->tentukanStatus($jarak, $jawabanBersih);
        $poin   = $this->hitungPoin($status);

        // Representasi Hijaiyah tetap dihasilkan untuk ditampilkan ke siswa
        // (mis. umpan balik visual), TIDAK dipakai untuk hitung jarak.
        $jawabanHijaiyah   = $this->phoneme->keHijaiyah($jawabanBersih);
        $referensiHijaiyah = $this->phoneme->keHijaiyah($referensiBersih);

        return [
            'jarak'              => $jarak,
            'status'             => $status,
            'poin'               => $poin,
            'jawaban_hijaiyah'   => $jawabanHijaiyah,
            'referensi_hijaiyah' => $referensiHijaiyah,
        ];
    }
}