@extends('layouts.app-murfodat')

@section('title', 'Edit Siswa')

@section('content')
<div class="admin-form-wrap">
<a href="/admin/users" class="btn btn-secondary" style="display:inline-flex;padding:6px 14px;font-size:12px;margin-bottom:16px">
    <x-icon name="chevron-left" size="12"/> Kembali
</a>

<div class="card">
    <div class="card-title">Data Siswa</div>
    <form method="POST" action="{{ route('admin.users.update', $siswa->id) }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $siswa->name) }}" class="form-input">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="grid2">
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">NIS</label>
                <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" class="form-input">
                @error('nis')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-select">
                    @foreach(['VII','VIII','IX'] as $k)
                    <option value="{{ $k }}" {{ old('kelas', $siswa->kelas) === $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                    @endforeach
                </select>
                @error('kelas')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="btn-row" style="margin-top:20px">
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            <a href="/admin/users" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<div class="card" style="margin-top:16px">
    <div class="card-title"><x-icon name="key" size="13"/> Reset Password</div>
    <div style="font-size:12px;color:var(--gray-400);margin-bottom:14px">
        Password siswa akan direset menjadi NIS-nya sendiri ({{ $siswa->nis }}). Beri tahu siswa untuk login memakai NIS sebagai password sementara.
    </div>
    <form method="POST" action="{{ route('admin.users.reset-password', $siswa->id) }}"
          onsubmit="return confirm('Reset password {{ $siswa->name }} menjadi NIS-nya sendiri?')">
        @csrf
        <button type="submit" class="btn btn-secondary">Reset password ke NIS</button>
    </form>
</div>

<div class="card" style="margin-top:16px">
    <div class="card-title" style="color:var(--red)"><x-icon name="alert-triangle" size="13"/> Zona Berbahaya</div>
    <div class="alert alert-error" style="display:block">
        Menghapus akun ini juga akan menghapus seluruh riwayat kuis dan skor milik siswa. Tindakan ini tidak dapat dibatalkan.
    </div>
    <form method="POST" action="{{ route('admin.users.hapus', $siswa->id) }}"
          onsubmit="return confirm('Hapus akun {{ $siswa->name }} beserta seluruh riwayat kuisnya? Tindakan ini tidak bisa dibatalkan.')">
        @csrf
        <button type="submit" class="btn btn-secondary" style="color:var(--red);border-color:var(--red);margin-top:12px">Hapus akun siswa</button>
    </form>
</div>
</div>
@endsection
