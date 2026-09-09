<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mufrodat;
use App\Models\User;
use App\Models\QuizSession;
use App\Models\QuizAnswer;

class AdminController extends Controller
{
    public function index()
    {
        $totalMufrodat = Mufrodat::count();
        $totalSiswa    = User::where('role', 'siswa')->count();
        $totalSesi     = QuizSession::count();
        $totalJawaban  = QuizAnswer::count();

        $accuracy = 0;
        if ($totalJawaban > 0) {
            $benar = QuizAnswer::where('status', 'BENAR')->count();
            $accuracy = round(($benar / $totalJawaban) * 100);
        }

        $sesiTerbaru = QuizSession::with('user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMufrodat',
            'totalSiswa',
            'totalSesi',
            'totalJawaban',
            'accuracy',
            'sesiTerbaru'
        ));
    }
}
