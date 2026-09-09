<?php

namespace App\Services;

use App\Models\AlgorithmTestCase;

class AlgorithmEvaluationService
{
    protected LevenshteinService $levenshtein;

    protected const KELAS = ['BENAR', 'TYPO', 'SALAH'];

    public function __construct(LevenshteinService $levenshtein)
    {
        $this->levenshtein = $levenshtein;
    }

    public function evaluasi(): array
    {
        $kasusUji = AlgorithmTestCase::with('mufrodat')->get();

        // confusion[actual][predicted] = jumlah kasus
        $confusion = [];
        foreach (self::KELAS as $a) {
            foreach (self::KELAS as $p) {
                $confusion[$a][$p] = 0;
            }
        }

        $benarKlasifikasi = 0;
        $detail = [];

        foreach ($kasusUji as $kasus) {
            $m = $kasus->mufrodat;
            if (!$m) continue;

            $prediksi = $this->levenshtein->bandingkan($kasus->jawaban_uji, $m->latin, $m->arab);
            $statusPrediksi    = $prediksi['status'];
            $statusSeharusnya  = $kasus->status_seharusnya;

            $confusion[$statusSeharusnya][$statusPrediksi]++;
            $cocok = $statusPrediksi === $statusSeharusnya;
            if ($cocok) $benarKlasifikasi++;

            $detail[] = [
                'jawaban_uji'       => $kasus->jawaban_uji,
                'kata_asli'         => $m->latin,
                'jenis_variasi'     => $kasus->jenis_variasi,
                'status_seharusnya' => $statusSeharusnya,
                'status_prediksi'   => $statusPrediksi,
                'jarak'             => $prediksi['jarak'],
                'cocok'             => $cocok,
            ];
        }

        $total = $kasusUji->count();
        $accuracy = $total > 0 ? round(($benarKlasifikasi / $total) * 100, 2) : 0;

        $perKelas = [];
        $sumPrecision = 0;
        $sumRecall = 0;

        foreach (self::KELAS as $c) {
            $tp = $confusion[$c][$c];
            $fp = 0;
            $fn = 0;
            $tn = 0;

            foreach (self::KELAS as $a) {
                foreach (self::KELAS as $p) {
                    if ($a === $c && $p !== $c) $fn += $confusion[$a][$p];
                    if ($a !== $c && $p === $c) $fp += $confusion[$a][$p];
                    if ($a !== $c && $p !== $c) $tn += $confusion[$a][$p];
                }
            }

            $precision = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
            $recall    = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;

            $perKelas[$c] = [
                'tp' => $tp, 'fp' => $fp, 'fn' => $fn, 'tn' => $tn,
                'precision' => round($precision * 100, 2),
                'recall'    => round($recall * 100, 2),
            ];

            $sumPrecision += $precision;
            $sumRecall    += $recall;
        }

        $jumlahKelas = count(self::KELAS);

        return [
            'total'      => $total,
            'accuracy'   => $accuracy,
            'precision'  => round(($sumPrecision / $jumlahKelas) * 100, 2),
            'recall'     => round(($sumRecall / $jumlahKelas) * 100, 2),
            'error_rate' => round(100 - $accuracy, 2),
            'confusion'  => $confusion,
            'per_kelas'  => $perKelas,
            'detail'     => $detail,
        ];
    }
}
