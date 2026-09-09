<?php

namespace App\Http\Controllers;

use App\Models\Mufrodat;
use App\Services\LevenshteinService;
use App\Services\PhonemeMappingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MufrodatController extends Controller
{
    protected LevenshteinService $levenshtein;
    protected PhonemeMappingService $phoneme;

    public function __construct(LevenshteinService $levenshtein, PhonemeMappingService $phoneme)
    {
        $this->levenshtein = $levenshtein;
        $this->phoneme = $phoneme;
    }

    // Halaman cari kata
    public function index(Request $request)
    {
        $query    = $request->input('q', '');
        $kelas    = $request->input('kelas', '');
        $hasil    = collect();
        $autocorrect = false;

        if ($query) {
            $pencarianTerakhir = \App\Models\ActivityLog::where('user_id', Auth::id())
                ->where('tipe', 'cari')
                ->where('deskripsi', 'Mencari kata "' . $query . '"')
                ->where('created_at', '>=', now()->subMinutes(2))
                ->exists();

            if (!$pencarianTerakhir) {
                \App\Models\ActivityLog::create([
                    'user_id'   => Auth::id(),
                    'tipe'      => 'cari',
                    'deskripsi' => 'Mencari kata "' . $query . '"',
                ]);
            }

            $queryBersih   = $this->levenshtein->preprocessing($query);
            $threshold     = $this->levenshtein->getThreshold($queryBersih);

            // Ambil semua mufrodat sesuai filter kelas
            $semuaMufrodat = Mufrodat::when($kelas, fn($q) => $q->where('kelas', $kelas))->get();

            foreach ($semuaMufrodat as $mufrodat) {
                $latinBersih = $this->levenshtein->preprocessing($mufrodat->latin);
                $artiBersih  = $this->levenshtein->preprocessing($mufrodat->arti);

                $jarakEjaan = $this->levenshtein->hitungJarak($queryBersih, $latinBersih);
                $jarakArti  = $this->levenshtein->hitungJarak($queryBersih, $artiBersih);

                $jarakMin = min($jarakEjaan, $jarakArti);

                if ($jarakMin <= $threshold) {
                    $mufrodat->jarak = $jarakMin;
                    $hasil->push($mufrodat);
                    if ($jarakMin > 0) $autocorrect = true;
                }
            }

            // Urutkan dari jarak terkecil
            $hasil = $hasil->sortBy('jarak')->values();
        } else {
            $hasil = Mufrodat::when($kelas, fn($q) => $q->where('kelas', $kelas))
                ->orderBy('kelas')->orderBy('bab')->orderBy('id')
                ->paginate(24)
                ->withQueryString();
        }

        return view('mufrodat.index', compact('hasil', 'query', 'kelas', 'autocorrect'));
    }
}
