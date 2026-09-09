<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mufrodat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMufrodatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $kelas  = $request->input('kelas', '');

        $mufrodat = Mufrodat::when(
            $search,
            fn($q) =>
            $q->where('latin', 'like', "%$search%")
                ->orWhere('arti', 'like', "%$search%")
        )->when($kelas, fn($q) => $q->where('kelas', $kelas))
            ->orderBy('kelas')->orderBy('bab')
            ->paginate(20);

        return view('admin.mufrodat.index', compact('mufrodat', 'search', 'kelas'));
    }

    public function tambah()
    {
        return view('admin.mufrodat.form', ['mufrodat' => null]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'arab'  => 'required|unique:mufrodats,arab',
            'latin' => 'required',
            'arti'  => 'required',
            'jenis' => 'nullable|string',
            'kelas' => 'required|in:VII,VIII,IX',
            'bab'   => 'required|integer|min:1',
        ], [
            'arab.unique' => 'Kata Arab ini sudah ada di database (kemungkinan duplikat). Cek dulu lewat pencarian sebelum menambah kata baru.',
        ]);

        Mufrodat::create($request->only('arab', 'latin', 'arti', 'jenis', 'kelas', 'bab'));

        return redirect()->route('admin.mufrodat')->with('success', 'Mufrodat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $mufrodat = Mufrodat::findOrFail($id);
        return view('admin.mufrodat.form', compact('mufrodat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'arab'  => 'required|unique:mufrodats,arab,' . $id,
            'latin' => 'required',
            'arti'  => 'required',
            'jenis' => 'nullable|string',
            'kelas' => 'required|in:VII,VIII,IX',
            'bab'   => 'required|integer|min:1',
        ], [
            'arab.unique' => 'Kata Arab ini sudah dipakai oleh mufrodat lain (kemungkinan duplikat).',
        ]);

        $mufrodat = Mufrodat::findOrFail($id);
        $mufrodat->update($request->only('arab', 'latin', 'arti', 'jenis', 'kelas', 'bab'));

        return redirect()->route('admin.mufrodat')->with('success', 'Mufrodat berhasil diupdate!');
    }

    public function hapus($id)
    {
        Mufrodat::findOrFail($id)->delete();
        return redirect()->route('admin.mufrodat')->with('success', 'Mufrodat berhasil dihapus!');
    }

    public function uploadAudio(Request $request, $id)
    {
        $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,ogg|max:5120',
        ]);

        $mufrodat = Mufrodat::findOrFail($id);

        if ($mufrodat->audio) {
            Storage::disk('public')->delete($mufrodat->audio);
        }

        $path = $request->file('audio')->store('audio', 'public');
        $mufrodat->update(['audio' => $path]);

        return redirect()->route('admin.mufrodat')->with('success', 'Audio berhasil diupload!');
    }

    // Hapus audio saja (mufrodat-nya tetap ada)
    public function hapusAudio($id)
    {
        $mufrodat = Mufrodat::findOrFail($id);

        if ($mufrodat->audio) {
            Storage::disk('public')->delete($mufrodat->audio);
            $mufrodat->update(['audio' => null]);
        }

        return redirect()->route('admin.mufrodat.edit', $id)->with('success', 'Audio berhasil dihapus!');
    }
}
