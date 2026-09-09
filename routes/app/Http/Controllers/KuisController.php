<?php

namespace App\Http\Controllers;

use App\Models\Mufrodat;
use App\Models\QuizSession;
use App\Models\QuizAnswer;
use App\Models\Score;
use App\Services\LevenshteinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuisController extends Controller
{
    protected $levenshtein;

    public function __construct(LevenshteinService $levenshtein)
    {
        $this->levenshtein = $levenshtein;
    }

    public function pilih()
    {
        $user = Auth::user();
        $kelasUser = $user->kelas ?? 'VII';

        $babJudul = config('bab_judul');
        $babDraf = \App\Models\BabStatus::where('kelas', $kelasUser)
            ->where('status', 'draf')
            ->pluck('bab');

        $babs = Mufrodat::selectRaw('bab, count(*) as total_soal')
            ->where('kelas', $kelasUser)
            ->groupBy('bab')
            ->orderBy('bab')
            ->get()
            ->reject(fn($row) => $babDraf->contains((int) $row->bab))
            ->map(function ($row) use ($kelasUser, $user, $babJudul) {
                $dikerjakan = QuizAnswer::whereHas('session', fn($q) =>
                    $q->where('user_id', $user->id)
                        ->where('kelas', $kelasUser)
                        ->where('bab', $row->bab)
                )->distinct('mufrodat_id')->count('mufrodat_id');

                $row->soal_dikerjakan = min($dikerjakan, $row->total_soal);
                $row->persen = $row->total_soal > 0 ? round(($row->soal_dikerjakan / $row->total_soal) * 100) : 0;
                $row->judul = $babJudul[$kelasUser][$row->bab] ?? '';
                return $row;
            })
            ->values();

        $babSebelumnyaSelesai = true;
        $babs = $babs->map(function ($b) use (&$babSebelumnyaSelesai) {
            $b->terkunci = !$babSebelumnyaSelesai;
            $babSebelumnyaSelesai = $b->persen >= 100;
            return $b;
        });

        $totalSoalKelas      = $babs->sum('total_soal');
        $totalDikerjakanKelas = $babs->sum('soal_dikerjakan');
        $persenKelas = $totalSoalKelas > 0 ? round(($totalDikerjakanKelas / $totalSoalKelas) * 100) : 0;
        $pangkat = app(\App\Services\PangkatService::class)->hitung($persenKelas, $kelasUser);

        return view('kuis.pilih', compact('babs', 'kelasUser', 'pangkat'));
    }

    // Mulai sesi kuis
    public function mulai(Request $request)
    {
        $request->validate([
            'kelas' => 'required|in:VII,VIII,IX',
            'bab'   => 'required|integer',
        ]);

        $babDraf = \App\Models\BabStatus::where('kelas', $request->kelas)
            ->where('bab', $request->bab)
            ->where('status', 'draf')
            ->exists();

        if ($babDraf) {
            return back()->with('error', 'Bab ini belum diaktifkan oleh guru/admin.');
        }

        // Cegah sesi kuis dobel kalau tombol "Mulai Kuis" ke-klik 2x
        // (double click / koneksi lambat lalu diklik ulang). Kalau siswa
        // masih punya sesi yang belum selesai untuk kelas+bab yang sama,
        // lanjutkan sesi itu saja, jangan bikin baris QuizSession baru.
        $sesiBerjalanId = session('quiz_session_id');
        if ($sesiBerjalanId) {
            $sesiBerjalan = QuizSession::find($sesiBerjalanId);
            if (
                $sesiBerjalan
                && !$sesiBerjalan->selesai
                && $sesiBerjalan->user_id === Auth::id()
                && $sesiBerjalan->kelas === $request->kelas
                && (int) $sesiBerjalan->bab === (int) $request->bab
            ) {
                return redirect()->route('kuis.soal');
            }
        }

        $babUrutan = Mufrodat::selectRaw('bab, count(*) as total_soal')
            ->where('kelas', $request->kelas)
            ->groupBy('bab')
            ->orderBy('bab')
            ->get()
            ->reject(fn($row) => \App\Models\BabStatus::where('kelas', $request->kelas)
                ->where('bab', $row->bab)->where('status', 'draf')->exists());

        $babSebelumnyaSelesai = true;
        foreach ($babUrutan as $row) {
            $dikerjakan = QuizAnswer::whereHas('session', fn($q) =>
                $q->where('user_id', Auth::id())
                    ->where('kelas', $request->kelas)
                    ->where('bab', $row->bab)
            )->distinct('mufrodat_id')->count('mufrodat_id');
            $persen = $row->total_soal > 0 ? round((min($dikerjakan, $row->total_soal) / $row->total_soal) * 100) : 0;

            if ((int) $row->bab === (int) $request->bab) {
                if (!$babSebelumnyaSelesai) {
                    return back()->with('error', 'Selesaikan bab sebelumnya dulu sebelum membuka bab ini.');
                }
                break;
            }
            $babSebelumnyaSelesai = $persen >= 100;
        }

        $sudahDijawab = QuizAnswer::whereHas('session', fn($q) =>
            $q->where('user_id', Auth::id())
                ->where('kelas', $request->kelas)
                ->where('bab', $request->bab)
        )->distinct()->pluck('mufrodat_id');

        $belumDijawab = Mufrodat::where('kelas', $request->kelas)
            ->where('bab', $request->bab)
            ->whereNotIn('id', $sudahDijawab)
            ->inRandomOrder()
            ->take(10)
            ->get();

        if ($belumDijawab->count() < 10) {
            $kurang = 10 - $belumDijawab->count();
            $tambahan = Mufrodat::where('kelas', $request->kelas)
                ->where('bab', $request->bab)
                ->whereIn('id', $sudahDijawab)
                ->inRandomOrder()
                ->take($kurang)
                ->get();
            $soal = $belumDijawab->concat($tambahan)->shuffle();
        } else {
            $soal = $belumDijawab;
        }

        if ($soal->isEmpty()) {
            return back()->with('error', 'Tidak ada soal untuk kelas dan bab ini.');
        }

        // Simpan soal ke session
        session([
            'kuis_soal'  => $soal->pluck('id')->toArray(),
            'kuis_index' => 0,
            'kuis_kelas' => $request->kelas,
            'kuis_bab'   => $request->bab,
        ]);

        // Buat quiz session baru
        $quizSession = QuizSession::create([
            'user_id'    => Auth::id(),
            'kelas'      => $request->kelas,
            'bab'        => $request->bab,
            'total_soal' => $soal->count(),
        ]);

        session(['quiz_session_id' => $quizSession->id]);

        // Catat peringkat SEBELUM sesi ini dimulai. Dulu ini dihitung di
        // hasil(), tapi sekarang poin disetor per-soal (lihat method jawab()),
        // jadi kalau dihitung di hasil() angkanya sudah ikut naik duluan.
        // Disimpan di session supaya bisa dibandingkan nanti di hasil().
        $skorAwal = Score::where('user_id', Auth::id())->value('total_poin') ?? 0;
        $peringkatAwal = Score::whereHas('user', fn($q) => $q->where('kelas', $request->kelas))
            ->where('total_poin', '>', $skorAwal)
            ->count() + 1;
        session(['kuis_peringkat_awal' => $peringkatAwal]);

        return redirect()->route('kuis.soal');
    }

    // Tampilkan soal
    public function soal()
    {
        $soalIds  = session('kuis_soal', []);
        $index    = session('kuis_index', 0);

        if ($index >= count($soalIds)) {
            return redirect()->route('kuis.hasil');
        }

        $mufrodat = Mufrodat::find($soalIds[$index]);
        $total    = count($soalIds);

        $quizSession = QuizSession::find(session('quiz_session_id'));
        $poinSesi    = $quizSession->total_skor ?? 0;

        return view('kuis.soal', compact('mufrodat', 'index', 'total', 'poinSesi'));
    }

    // Proses jawaban
    public function jawab(Request $request)
    {
        $soalIds       = session('kuis_soal', []);
        $index         = session('kuis_index', 0);
        $quizSessionId = session('quiz_session_id');

        if (!isset($soalIds[$index])) {
            return redirect()->route('kuis.hasil');
        }

        $mufrodat = Mufrodat::find($soalIds[$index]);

        if (!$mufrodat) {
            return redirect()->route('kuis.hasil');
        }

        // Cegah jawaban dobel kalau tombol submit ke-klik 2x untuk soal yang
        // sama (double click / koneksi lambat lalu diklik ulang). Kalau soal
        // ini di sesi ini sudah pernah dijawab, jangan buat baris/poin baru
        // lagi -- cukup tampilkan lagi hasil yang sudah tercatat.
        $jawabanSebelumnya = QuizAnswer::where('quiz_session_id', $quizSessionId)
            ->where('mufrodat_id', $mufrodat->id)
            ->first();

        if ($jawabanSebelumnya) {
            session([
                'hasil_jawaban' => [
                    'arab'      => $mufrodat->arab,
                    'latin'     => $mufrodat->latin,
                    'arti'      => $mufrodat->arti,
                    'jawaban'   => $jawabanSebelumnya->jawaban_siswa,
                    'status'    => $jawabanSebelumnya->status,
                    'jarak'     => $jawabanSebelumnya->jarak_levenshtein,
                    'poin'      => $jawabanSebelumnya->poin,
                    'index'     => $index + 1,
                    'total'     => count($soalIds),
                ]
            ]);
            return redirect()->route('kuis.review');
        }

        $hasilBanding = $this->levenshtein->bandingkan(
            $request->input('lewati') ? '' : ($request->jawaban ?? ''),
            $mufrodat->latin,
            $mufrodat->arab
        );

        $jarak  = $hasilBanding['jarak'];
        $status = $hasilBanding['status'];
        $poin   = $hasilBanding['poin'];

        // Simpan jawaban
        QuizAnswer::create([
            'quiz_session_id'   => $quizSessionId,
            'mufrodat_id'       => $mufrodat->id,
            'jawaban_siswa'     => $request->input('lewati') ? '' : ($request->jawaban ?? ''),
            'jawaban_referensi' => $mufrodat->latin,
            'jarak_levenshtein' => $jarak,
            'status'            => $status,
            'poin'              => $poin,
        ]);

        // Update session index
        session(['kuis_index' => $index + 1]);

        // Update quiz session skor
        $quizSession = QuizSession::find($quizSessionId);
        $quizSession->increment('total_skor', $poin);
        $quizSession->increment('total_' . strtolower($status));

        // Setor poin ke Score SEKARANG JUGA (per jawaban), bukan menunggu
        // siswa sampai ke halaman hasil. Ini memastikan siswa yang berhenti
        // di tengah jalan (tab ditutup, sesi habis, dll.) tetap poinnya
        // masuk dan namanya tetap muncul di leaderboard, sesuai progres
        // yang sudah benar-benar dia kerjakan.
        Score::firstOrCreate(
            ['user_id' => Auth::id()],
            ['total_poin' => 0, 'total_sesi' => 0]
        )->increment('total_poin', $poin);

        // Simpan hasil jawaban ke session untuk ditampilkan
        session([
            'hasil_jawaban' => [
                'arab'      => $mufrodat->arab,
                'latin'     => $mufrodat->latin,
                'arti'      => $mufrodat->arti,
                'jawaban'   => $request->jawaban,
                'status'    => $status,
                'jarak'     => $jarak,
                'poin'      => $poin,
                'index'     => $index + 1,
                'total'     => count($soalIds),
            ]
        ]);

        return redirect()->route('kuis.review');
    }

    public function review()
    {
        $hasil = session('hasil_jawaban');
        if (!$hasil) return redirect()->route('kuis.pilih');
        return view('kuis.review', compact('hasil'));
    }


    public function preview(Request $request)
    {
        $soalIds = session('kuis_soal', []);
        $index   = session('kuis_index', 0);

        if (!isset($soalIds[$index])) {
            return response()->json(['status' => null]);
        }

        $mufrodat = Mufrodat::find($soalIds[$index]);
        $jawaban  = trim($request->input('jawaban', ''));

        if (!$mufrodat || $jawaban === '') {
            return response()->json(['status' => null]);
        }

        $hasil = $this->levenshtein->bandingkan($jawaban, $mufrodat->latin, $mufrodat->arab);

        $jawabanBersih = $this->levenshtein->preprocessing($jawaban);
        $threshold     = $this->levenshtein->getThreshold($jawabanBersih);

        $saran = [];
        if ($hasil['status'] !== 'BENAR') {
            $saran = Mufrodat::where('kelas', $mufrodat->kelas)
                ->where('id', '!=', $mufrodat->id)
                ->get()
                ->map(function ($m) use ($jawabanBersih) {
                    $latinBersih = $this->levenshtein->preprocessing($m->latin);
                    return [
                        'latin' => $m->latin,
                        'jarak' => $this->levenshtein->hitungJarak($jawabanBersih, $latinBersih),
                    ];
                })
                ->sortBy('jarak')
                ->unique('latin')
                ->take(3)
                ->pluck('latin')
                ->values();
        }

        return response()->json([
            'status'    => $hasil['status'],
            'jarak'     => $hasil['jarak'],
            'poin'      => $hasil['poin'],
            'panjang'   => strlen($jawabanBersih),
            'threshold' => $threshold,
            'saran'     => $saran,
        ]);
    }
    
    // Halaman hasil kuis
    public function hasil()
    {
        $quizSessionId = session('quiz_session_id');
        $quizSession   = QuizSession::with('answers.mufrodat')->find($quizSessionId);

        if (!$quizSession) {
            return redirect()->route('kuis.pilih');
        }

        $user = Auth::user();

        // Poin per-soal sudah disetor langsung ke Score sejak KuisController::jawab(),
        // supaya siswa yang berhenti di tengah jalan tetap poinnya tercatat.
        // Di sini kita HANYA menandai sesi selesai (dan menambah total_sesi),
        // dan itupun cuma sekali walau halaman hasil dibuka berkali-kali.
        $score = Score::firstOrCreate(
            ['user_id' => $user->id],
            ['total_poin' => 0, 'total_sesi' => 0]
        );

        if (!$quizSession->selesai) {
            $score->increment('total_sesi');
            $quizSession->update(['selesai' => true]);
        }

        $peringkatSesudah = Score::whereHas('user', fn($q) => $q->where('kelas', $user->kelas))
            ->where('total_poin', '>', $score->total_poin)
            ->count() + 1;

        // Peringkat sebelum sesi ini dimulai (disimpan di mulai()). Kalau
        // tidak ada (mis. sesi lama sebelum patch ini), anggap sama dengan
        // sekarang supaya tidak salah mencatat "naik peringkat".
        $peringkatSebelum = session('kuis_peringkat_awal', $peringkatSesudah);

        if ($peringkatSesudah < $peringkatSebelum) {
            \App\Models\ActivityLog::create([
                'user_id'   => $user->id,
                'tipe'      => 'peringkat',
                'deskripsi' => "Naik ke peringkat #{$peringkatSesudah} leaderboard kelas",
            ]);
        }

        return view('kuis.hasil', compact('quizSession'));
    }
}
