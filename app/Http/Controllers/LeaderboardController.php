<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $kelas = $request->input('kelas', Auth::user()->kelas ?? '');

        // PENTING: query mulai dari User, bukan dari Score.
        // Sebelumnya query mulai dari Score, sehingga siswa yang belum
        // pernah punya baris di tabel scores (mis. belum sempat menjawab
        // satu soal pun) hilang total dari daftar, padahal dia tetap
        // anggota kelas yang sah dengan 0 poin.
        $ranking = User::where('role', 'siswa')
            ->when($kelas, fn($q) => $q->where('kelas', $kelas))
            ->with('score')
            ->get()
            ->map(function ($user) {
                $user->total_poin = $user->score->total_poin ?? 0;
                $user->total_sesi = $user->score->total_sesi ?? 0;
                return $user;
            })
            // Urutan tampilan: poin tertinggi dulu, seri poin diurut nama
            // supaya urutannya stabil (tidak berubah-ubah tiap refresh).
            ->sortBy([
                fn($a, $b) => $b->total_poin <=> $a->total_poin,
                fn($a, $b) => $a->name <=> $b->name,
            ])
            ->values();

        // Peringkat pakai "competition ranking" -- SAMA PERSIS dengan rumus
        // di DashboardController & KuisController::hasil() -- supaya siswa
        // yang poinnya seri berada di kelompok peringkat yang sama, dan
        // angka #peringkat konsisten di semua halaman.
        $ranking = $ranking->map(function ($user) use ($ranking) {
            $user->peringkat = $ranking->where('total_poin', '>', $user->total_poin)->count() + 1;
            return $user;
        });

        // Cari posisi user yang sedang login
        $myRank = $ranking->firstWhere('id', Auth::id());

        return view('leaderboard', compact('ranking', 'myRank', 'kelas'));
    }
}
