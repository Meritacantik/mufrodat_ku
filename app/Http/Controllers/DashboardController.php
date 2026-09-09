<?php

namespace App\Http\Controllers;

use App\Models\Mufrodat;
use App\Models\QuizSession;
use App\Models\QuizAnswer;
use App\Models\Score;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Total poin dan peringkat
        $score = Score::where('user_id', $user->id)->first();
        $totalPoin = $score ? $score->total_poin : 0;
        $totalSesi = $score ? $score->total_sesi : 0;

        // Hitung peringkat (di antara siswa sekelas, sesuai konteks Leaderboard default)
        $peringkat = Score::whereHas('user', fn($q) => $q->where('kelas', $user->kelas))
            ->where('total_poin', '>', $totalPoin)->count() + 1;
        $totalSiswaKelas = \App\Models\User::where('kelas', $user->kelas)->where('role', 'siswa')->count();

        // Poin yang didapat hari ini (dari sesi kuis yang selesai hari ini)
        $poinHariIni = QuizSession::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->sum('total_skor');

        $totalMufrodat = Mufrodat::count();

        // Progress per kelas
        $progres = [];
        foreach (['VII', 'VIII', 'IX'] as $kelas) {
            $totalKelas = Mufrodat::where('kelas', $kelas)->count();
            $benar = QuizAnswer::whereHas('session', fn($q) =>
                $q->where('user_id', $user->id)->where('kelas', $kelas)
            )->where('status', 'BENAR')->count();

            $progres[$kelas] = [
                'total'  => $totalKelas,
                'benar'  => $benar,
                'persen' => $totalKelas > 0 ? min(100, round(($benar / $totalKelas) * 100)) : 0,
            ];
        }

        // Total kata dikuasai
        $totalDikuasai = QuizAnswer::whereHas('session', fn($q) =>
            $q->where('user_id', $user->id)
        )->where('status', 'BENAR')->distinct('mufrodat_id')->count('mufrodat_id');

        $babJudul = config('bab_judul');
        $semuaBab = Mufrodat::selectRaw('kelas, bab, count(*) as total_soal')
            ->where('kelas', $user->kelas)
            ->groupBy('kelas', 'bab')
            ->orderBy('kelas')->orderBy('bab')
            ->get()
            ->map(function ($row) use ($user, $babJudul) {
                $dikerjakan = QuizAnswer::whereHas('session', fn($q) =>
                    $q->where('user_id', $user->id)
                        ->where('kelas', $row->kelas)
                        ->where('bab', $row->bab)
                )->distinct('mufrodat_id')->count('mufrodat_id');

                $row->soal_dikerjakan = min($dikerjakan, $row->total_soal);
                $row->persen = $row->total_soal > 0 ? round(($row->soal_dikerjakan / $row->total_soal) * 100) : 0;
                $row->judul = $babJudul[$row->kelas][$row->bab] ?? '';
                return $row;
            });

        $lanjutkanBelajar = $semuaBab->filter(fn($b) => $b->soal_dikerjakan > 0 && $b->soal_dikerjakan < $b->total_soal)
            ->concat($semuaBab->filter(fn($b) => $b->soal_dikerjakan === 0))
            ->take(3)
            ->values();

        $aktivitasKuis = QuizSession::where('user_id', $user->id)
            ->withCount('answers')
            ->orderByDesc('created_at')
            ->take(20) // ambil kandidat lebih banyak, nanti disaring yang benar-benar tuntas
            ->get()
            ->filter(fn($a) => $a->answers_count >= $a->total_soal) // hanya sesi yang benar-benar tuntas
            ->take(3)
            ->map(fn($a) => (object) [
                'tipe'       => 'kuis',
                'deskripsi'  => 'Menyelesaikan kuis Bab ' . $a->bab . ' - ' . ($babJudul[$a->kelas][$a->bab] ?? ''),
                'created_at' => $a->created_at,
                'poin'       => $a->total_skor,
            ]);

        $aktivitasLog = \App\Models\ActivityLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(3)
            ->get()
            ->map(fn($a) => (object) [
                'tipe'       => $a->tipe,
                'deskripsi'  => $a->deskripsi,
                'created_at' => $a->created_at,
                'poin'       => null,
            ]);

        $aktivitas = $aktivitasKuis->concat($aktivitasLog)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('dashboard', compact(
            'user', 'totalPoin', 'totalSesi', 'peringkat', 'totalSiswaKelas',
            'poinHariIni', 'totalMufrodat',
            'progres', 'totalDikuasai', 'aktivitas', 'lanjutkanBelajar'
        ));
    }
}