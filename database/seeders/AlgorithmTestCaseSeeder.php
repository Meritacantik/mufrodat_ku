<?php

namespace Database\Seeders;

use App\Models\Mufrodat;
use App\Services\LevenshteinService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Menghasilkan data uji variasi typo untuk evaluasi akurasi algoritma
 * (Bab III - teknik analisis data: "50-100 data variasi typo dari
 * dataset mufrodat yang telah disusun").
 *
 * Untuk tiap kata sampel dibuat 4 variasi dengan GROUND TRUTH yang
 * diketahui pasti (bukan ditebak):
 *   1) identik            -> status seharusnya BENAR (0 edit)
 *   2) typo 1 karakter    -> status seharusnya TYPO/SALAH tergantung
 *                            threshold adaptif (Tabel 4.7) untuk panjang
 *                            kata hasil edit
 *   3) typo 2 karakter    -> idem, 2 edit
 *   4) kata acak sepanjang kata asli -> status seharusnya SALAH
 *
 * Ground truth dihitung dari JUMLAH EDIT YANG SENGAJA DIMASUKKAN (k),
 * bukan dari hasil sistem itu sendiri - supaya pengujian ini benar-benar
 * memvalidasi apakah pipeline (preprocessing + konversi Hijaiyah +
 * hitung distance) menghasilkan jarak yang SESUAI dengan jumlah edit
 * sungguhan, bukan sekadar mencocokkan kode dengan dirinya sendiri.
 */
class AlgorithmTestCaseSeeder extends Seeder
{
    private const ALFABET = 'abcdefghijklmnopqrstuvwxyz';

    public function run(): void
    {
        DB::table('algorithm_test_cases')->truncate();

        $levenshtein = app(LevenshteinService::class);

        // Ambil sampel tersebar dari ketiga kelas (VII/VIII/IX) supaya
        // representatif, total sekitar 25 kata induk x ~3-4 variasi
        // = 75-100 data uji sesuai anjuran Bab III.
        $sampel = collect();
        foreach (['VII', 'VIII', 'IX'] as $kelas) {
            $sampel = $sampel->merge(
                Mufrodat::where('kelas', $kelas)->inRandomOrder()->limit(9)->get()
            );
        }

        $rows = [];

        foreach ($sampel as $m) {
            $asli = $levenshtein->preprocessing($m->latin);
            if (strlen($asli) < 2) {
                continue; // kata terlalu pendek untuk typo yang bermakna
            }

            // 1) Identik -> BENAR
            $rows[] = [
                'mufrodat_id'        => $m->id,
                'jawaban_uji'        => $asli,
                'jenis_variasi'      => 'identik',
                'status_seharusnya'  => 'BENAR',
            ];

            // 2) Typo 1 karakter
            $typo1 = $this->substitusiKarakter($asli, 1);
            $rows[] = [
                'mufrodat_id'        => $m->id,
                'jawaban_uji'        => $typo1,
                'jenis_variasi'      => 'typo_1_karakter',
                'status_seharusnya'  => $this->labelSeharusnya($levenshtein, $typo1, 1),
            ];

            // 3) Typo 2 karakter (hanya untuk kata yang cukup panjang)
            if (strlen($asli) >= 4) {
                $typo2 = $this->substitusiKarakter($asli, 2);
                $rows[] = [
                    'mufrodat_id'        => $m->id,
                    'jawaban_uji'        => $typo2,
                    'jenis_variasi'      => 'typo_2_karakter',
                    'status_seharusnya'  => $this->labelSeharusnya($levenshtein, $typo2, 2),
                ];
            }

            // 4) Kata acak -> SALAH
            $acak = $this->kataAcak(strlen($asli));
            $rows[] = [
                'mufrodat_id'        => $m->id,
                'jawaban_uji'        => $acak,
                'jenis_variasi'      => 'kata_acak',
                'status_seharusnya'  => 'SALAH',
            ];
        }

        $now = now();
        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('algorithm_test_cases')->insert(array_map(
                fn($r) => $r + ['created_at' => $now, 'updated_at' => $now],
                $chunk
            ));
        }
    }

    // Ganti $k karakter pada posisi BERBEDA dengan huruf lain, sehingga
    // jarak Levenshtein (Latin) antara $kata asli dan hasilnya PASTI
    // sama dengan $k (bukan perkiraan).
    private function substitusiKarakter(string $kata, int $k): string
    {
        $huruf   = str_split($kata);
        $panjang = count($huruf);
        $k       = min($k, $panjang);

        $posisi = collect(range(0, $panjang - 1))->shuffle()->take($k);

        foreach ($posisi as $i) {
            do {
                $baru = self::ALFABET[random_int(0, 25)];
            } while ($baru === $huruf[$i]);
            $huruf[$i] = $baru;
        }

        return implode('', $huruf);
    }

    private function kataAcak(int $panjang): string
    {
        $panjang = max(3, $panjang);
        $hasil = '';
        for ($i = 0; $i < $panjang; $i++) {
            $hasil .= self::ALFABET[random_int(0, 25)];
        }
        return $hasil;
    }

    // Ground truth: berdasarkan jumlah edit $k yang SENGAJA dimasukkan,
    // dibandingkan terhadap threshold adaptif (Tabel 4.7) untuk panjang
    // kata hasil edit.
    private function labelSeharusnya(LevenshteinService $levenshtein, string $hasilEdit, int $k): string
    {
        $threshold = $levenshtein->getThreshold($hasilEdit);
        return $k <= $threshold ? 'TYPO' : 'SALAH';
    }
}
