<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BabStatus;
use App\Models\Mufrodat;

class AdminSesiKuisController extends Controller
{
    // Halaman Kelola Sesi Kuis: menampilkan setiap (kelas, bab) yang ada
    // di data mufrodat, jumlah soalnya, dan status aktif/draf-nya.
    public function index()
    {
        $babJudul = config('bab_judul');

        $daftarBab = Mufrodat::selectRaw('kelas, bab, count(*) as jumlah_soal')
            ->groupBy('kelas', 'bab')
            ->orderBy('kelas')
            ->orderBy('bab')
            ->get()
            ->map(function ($row) use ($babJudul) {
                $existing = BabStatus::where('kelas', $row->kelas)->where('bab', $row->bab)->first();
                $row->status = $existing->status ?? 'aktif';
                $row->judul = $babJudul[$row->kelas][$row->bab] ?? '';
                return $row;
            });

        return view('admin.sesi-kuis', compact('daftarBab'));
    }

    // Toggle status aktif <-> draf untuk satu (kelas, bab).
    public function toggle($kelas, $bab)
    {
        $babStatus = BabStatus::firstOrCreate(
            ['kelas' => $kelas, 'bab' => $bab],
            ['status' => 'aktif']
        );

        $babStatus->status = $babStatus->status === 'aktif' ? 'draf' : 'aktif';
        $babStatus->save();

        return redirect()->route('admin.sesi-kuis')->with('success', 'Status bab berhasil diperbarui!');
    }
}
