<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Score;
use App\Models\QuizSession;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->input('q', '');

        $users = User::where('role', 'siswa')
            ->when($search, fn($q) => $q->where(fn($w) =>
                $w->where('name', 'like', "%$search%")->orWhere('nis', 'like', "%$search%")
            ))
            ->with('score')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users', compact('users', 'search'));
    }

    // Tampilkan form edit data siswa (nama, NIS, kelas)
    public function edit($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        return view('admin.users_form', compact('siswa'));
    }

    // Simpan perubahan nama/NIS/kelas siswa
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'nis'   => 'required|string|max:30|unique:users,nis,' . $siswa->id,
            'kelas' => 'required|in:VII,VIII,IX',
        ]);

        $siswa->update([
            'name'  => $request->name,
            'nis'   => $request->nis,
            'email' => $request->nis . '@mufrodatku.local',
            'kelas' => $request->kelas,
        ]);

        return redirect()->route('admin.users')->with('success', 'Data siswa berhasil diperbarui!');
    }

    // Reset password siswa ke NIS-nya sendiri (dipakai kalau siswa lupa password)
    public function resetPassword($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $siswa->update(['password' => Hash::make($siswa->nis)]);

        return redirect()->route('admin.users')
            ->with('success', "Password {$siswa->name} berhasil direset menjadi NIS-nya ({$siswa->nis}).");
    }

    // Hapus akun siswa beserta seluruh riwayat kuis & skornya (cascade)
    public function hapus($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.users')->with('success', 'Akun siswa berhasil dihapus.');
    }
}
