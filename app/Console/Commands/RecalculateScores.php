<?php

namespace App\Console\Commands;

use App\Models\QuizSession;
use App\Models\Score;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateScores extends Command
{
    protected $signature = 'scores:recalculate {--dry-run}';

    protected $description = 'Hitung ulang tabel scores dari data quiz_sessions/quiz_answers yang sebenarnya. '
        . 'Dipakai sekali untuk memperbaiki data lama yang sempat tidak tersimpan '
        . '(mis. siswa berhenti sebelum sampai halaman hasil, sebelum patch per-jawaban diterapkan).';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $poinPerUser = DB::table('quiz_answers')
            ->join('quiz_sessions', 'quiz_sessions.id', '=', 'quiz_answers.quiz_session_id')
            ->select('quiz_sessions.user_id', DB::raw('SUM(quiz_answers.poin) as total_poin'))
            ->groupBy('quiz_sessions.user_id')
            ->get()
            ->keyBy('user_id');

        $sesiSelesaiPerUser = QuizSession::withCount('answers')
            ->get()
            ->filter(fn($s) => $s->answers_count >= $s->total_soal)
            ->groupBy('user_id')
            ->map->count();

        $semuaUserId = $poinPerUser->keys()
            ->merge($sesiSelesaiPerUser->keys())
            ->unique();

        $berubah = 0;

        foreach ($semuaUserId as $userId) {
            $poinBenar = (int) ($poinPerUser[$userId]->total_poin ?? 0);
            $sesiBenar = (int) ($sesiSelesaiPerUser[$userId] ?? 0);

            $score = Score::firstOrNew(['user_id' => $userId]);
            $poinLama = $score->total_poin ?? 0;
            $sesiLama = $score->total_sesi ?? 0;

            if ($poinLama != $poinBenar || $sesiLama != $sesiBenar) {
                $berubah++;
                $this->line("User #{$userId}: poin {$poinLama} -> {$poinBenar}, sesi {$sesiLama} -> {$sesiBenar}");

                if (!$dryRun) {
                    $score->total_poin = $poinBenar;
                    $score->total_sesi = $sesiBenar;
                    $score->save();
                }
            }

            if (!$dryRun) {
                QuizSession::where('user_id', $userId)
                    ->withCount('answers')
                    ->get()
                    ->filter(fn($s) => $s->answers_count >= $s->total_soal && !$s->selesai)
                    ->each(fn($s) => $s->update(['selesai' => true]));
            }
        }

        $this->info($dryRun
            ? "Selesai (dry-run). {$berubah} siswa akan berubah datanya kalau dijalankan tanpa --dry-run."
            : "Selesai. {$berubah} siswa diperbaiki datanya di tabel scores.");

        return self::SUCCESS;
    }
}
