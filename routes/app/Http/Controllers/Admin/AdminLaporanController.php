<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAnswer;
use App\Models\QuizSession;
use App\Services\AlgorithmEvaluationService;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index(Request $request, AlgorithmEvaluationService $evaluator)
    {
        $totalJawaban = QuizAnswer::count();
        $benar  = QuizAnswer::where('status', 'BENAR')->count();
        $typo   = QuizAnswer::where('status', 'TYPO')->count();
        $salah  = QuizAnswer::where('status', 'SALAH')->count();

        $distribusiBenar  = $totalJawaban > 0 ? round(($benar / $totalJawaban) * 100, 2) : 0;
        $distribusiTypo   = $totalJawaban > 0 ? round(($typo / $totalJawaban) * 100, 2) : 0;
        $distribusiSalah  = $totalJawaban > 0 ? round(($salah / $totalJawaban) * 100, 2) : 0;

        $evaluasiAlgoritma = $evaluator->evaluasi();


        $logJawaban = QuizAnswer::with(['session.user', 'mufrodat'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $babJudul = config('bab_judul');
        $search = $request->input('cari', '');

        $siswaAktif = QuizSession::distinct('user_id')->count('user_id');
        $kuisDikerjakan = QuizSession::count();
        $rataRataSkor = QuizSession::whereRaw('total_benar + total_typo + total_salah >= total_soal')
            ->selectRaw('AVG(total_skor / (total_soal * 10) * 100) as rata')
            ->value('rata');
        $rataRataSkor = $rataRataSkor ? round($rataRataSkor) : 0;

        $sesiKuis = QuizSession::with('user')
            ->when($search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%$search%")))
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($s) use ($babJudul) {
                $terjawab = $s->total_benar + $s->total_typo + $s->total_salah;
                $skorPersen = $s->total_soal > 0 ? round(($s->total_skor / ($s->total_soal * 10)) * 100) : 0;

                if ($terjawab < $s->total_soal) {
                    $status = 'Belum selesai';
                } elseif ($skorPersen < 60) {
                    $status = 'Perlu remedial';
                } else {
                    $status = 'Selesai';
                }

                $s->skor_persen = $skorPersen;
                $s->status_laporan = $status;
                $s->judul_bab = $babJudul[$s->kelas][$s->bab] ?? '';
                return $s;
            });

        $butuhPerhatian = $sesiKuis->where('status_laporan', 'Perlu remedial')->pluck('user_id')->unique()->count();

        return view('admin.laporan', compact(
            'totalJawaban',
            'benar',
            'typo',
            'salah',
            'distribusiBenar',
            'distribusiTypo',
            'distribusiSalah',
            'evaluasiAlgoritma',
            'logJawaban',
            'siswaAktif',
            'kuisDikerjakan',
            'rataRataSkor',
            'butuhPerhatian',
            'sesiKuis',
            'search'
        ));
    }

    // Ekspor log sesi kuis ke CSV
    public function eksporCsv()
    {
        $babJudul = config('bab_judul');
        $sesiKuis = QuizSession::with('user')->orderByDesc('created_at')->get();

        $filename = 'laporan-kuis-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($sesiKuis, $babJudul) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Siswa', 'Kelas', 'Bab', 'Judul Bab', 'Skor (%)', 'Waktu']);
            foreach ($sesiKuis as $s) {
                $skorPersen = $s->total_soal > 0 ? round(($s->total_skor / ($s->total_soal * 10)) * 100) : 0;
                fputcsv($file, [
                    $s->user->name ?? '-',
                    $s->kelas,
                    $s->bab,
                    $babJudul[$s->kelas][$s->bab] ?? '',
                    $skorPersen,
                    $s->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}