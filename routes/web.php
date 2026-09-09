<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MufrodatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KuisController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminMufrodatController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminSesiKuisController;

// Route Admin
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/mufrodat', [AdminMufrodatController::class, 'index'])->name('admin.mufrodat');
    Route::get('/mufrodat/tambah', [AdminMufrodatController::class, 'tambah'])->name('admin.mufrodat.tambah');
    Route::post('/mufrodat/simpan', [AdminMufrodatController::class, 'simpan'])->name('admin.mufrodat.simpan');
    Route::get('/mufrodat/edit/{id}', [AdminMufrodatController::class, 'edit'])->name('admin.mufrodat.edit');
    Route::post('/mufrodat/update/{id}', [AdminMufrodatController::class, 'update'])->name('admin.mufrodat.update');
    Route::post('/mufrodat/hapus/{id}', [AdminMufrodatController::class, 'hapus'])->name('admin.mufrodat.hapus');
    Route::post('/mufrodat/upload-audio/{id}', [AdminMufrodatController::class, 'uploadAudio'])->name('admin.mufrodat.audio');
    Route::post('/mufrodat/hapus-audio/{id}', [AdminMufrodatController::class, 'hapusAudio'])->name('admin.mufrodat.hapus-audio');
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/update/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/reset-password/{id}', [AdminUserController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::post('/users/hapus/{id}', [AdminUserController::class, 'hapus'])->name('admin.users.hapus');
    Route::get('/sesi-kuis', [AdminSesiKuisController::class, 'index'])->name('admin.sesi-kuis');
    Route::post('/sesi-kuis/{kelas}/{bab}/toggle', [AdminSesiKuisController::class, 'toggle'])->name('admin.sesi-kuis.toggle');
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/ekspor', [AdminLaporanController::class, 'eksporCsv'])->name('admin.laporan.ekspor');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Semua fitur inti siswa wajib login (pencarian, kuis, leaderboard, profil)
Route::middleware('auth')->group(function () {
    Route::get('/mufrodat', [MufrodatController::class, 'index'])->name('mufrodat.index');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    Route::get('/kuis', [KuisController::class, 'pilih'])->name('kuis.pilih');
    Route::post('/kuis/mulai', [KuisController::class, 'mulai'])->name('kuis.mulai');
    Route::get('/kuis/soal', [KuisController::class, 'soal'])->name('kuis.soal');
    Route::post('/kuis/preview', [KuisController::class, 'preview'])->name('kuis.preview');
    Route::post('/kuis/jawab', [KuisController::class, 'jawab'])->name('kuis.jawab');
    Route::get('/kuis/hasil', [KuisController::class, 'hasil'])->name('kuis.hasil');
    Route::get('/kuis/review', [KuisController::class, 'review'])->name('kuis.review');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
