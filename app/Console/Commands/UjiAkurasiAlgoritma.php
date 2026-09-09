<?php

namespace App\Console\Commands;

use App\Services\AlgorithmEvaluationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UjiAkurasiAlgoritma extends Command
{
    protected $signature = 'algoritma:uji {--seed : Generate ulang data uji sebelum evaluasi}';
    protected $description = 'Jalankan confusion matrix evaluasi akurasi LevenshteinService (Bab III & V)';

    public function handle(AlgorithmEvaluationService $evaluator)
    {
        if ($this->option('seed') || !\App\Models\AlgorithmTestCase::exists()) {
            $this->info('Membuat data uji...');
            Artisan::call('db:seed', ['--class' => \Database\Seeders\AlgorithmTestCaseSeeder::class]);
        }

        $hasil = $evaluator->evaluasi();

        $this->newLine();
        $this->info("Total data uji : {$hasil['total']}");
        $this->info("Accuracy       : {$hasil['accuracy']}%");
        $this->info("Precision      : {$hasil['precision']}%  (macro-average)");
        $this->info("Recall         : {$hasil['recall']}%  (macro-average)");
        $this->info("Error Rate     : {$hasil['error_rate']}%");
        $this->newLine();

        $this->table(
            ['Kelas', 'TP', 'FP', 'FN', 'TN', 'Precision', 'Recall'],
            collect($hasil['per_kelas'])->map(fn($v, $k) => [
                $k, $v['tp'], $v['fp'], $v['fn'], $v['tn'], $v['precision'].'%', $v['recall'].'%',
            ])->values()
        );

        $this->newLine();
        $this->comment('Confusion Matrix (baris = seharusnya, kolom = prediksi sistem):');
        $header = ['Aktual \\ Prediksi', 'BENAR', 'TYPO', 'SALAH'];
        $rows = [];
        foreach (['BENAR', 'TYPO', 'SALAH'] as $a) {
            $rows[] = [$a, $hasil['confusion'][$a]['BENAR'], $hasil['confusion'][$a]['TYPO'], $hasil['confusion'][$a]['SALAH']];
        }
        $this->table($header, $rows);

        $salah = collect($hasil['detail'])->reject(fn($d) => $d['cocok']);
        if ($salah->isNotEmpty()) {
            $this->newLine();
            $this->warn("Kasus yang meleset dari ekspektasi ({$salah->count()}):");
            $this->table(
                ['Jawaban uji', 'Kata asli', 'Jenis', 'Seharusnya', 'Prediksi', 'Jarak'],
                $salah->map(fn($d) => [
                    $d['jawaban_uji'], $d['kata_asli'], $d['jenis_variasi'],
                    $d['status_seharusnya'], $d['status_prediksi'], $d['jarak'],
                ])->values()
            );
        }

        return self::SUCCESS;
    }
}
