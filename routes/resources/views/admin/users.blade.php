@extends('layouts.app-murfodat')

@section('title', 'Data Siswa')

@section('content')
@if(session('success'))
<div class="alert alert-success"><x-icon name="check-circle" size="14"/> {{ session('success') }}</div>
@endif

<div style="text-align:right;margin-bottom:16px">
    <span style="font-size:12px;color:var(--gray-500)">{{ $users->count() }} siswa terdaftar</span>
</div>

<form method="GET" class="btn-row" style="margin-bottom:16px">
    <input type="text" name="q" value="{{ $search }}" placeholder="Cari siswa (nama/NIS)..." class="form-input" style="flex:1">
    <button type="submit" class="btn btn-primary">Cari</button>
</form>

<div class="card" style="padding:0">
    <table class="tbl">
        <thead>
            <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Total poin</th><th>Kuis selesai</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td style="color:var(--gray-500)">{{ $u->nis ?? '-' }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="avatar">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
                        {{ $u->name }}
                    </div>
                </td>
                <td><span class="pill {{ $u->kelas === 'VII' ? 'pill-green' : ($u->kelas === 'VIII' ? 'pill-blue' : 'pill-amber') }}">{{ $u->kelas ?? '-' }}</span></td>
                <td><b style="color:var(--green)">{{ $u->score ? number_format($u->score->total_poin) : 0 }}</b></td>
                <td>{{ $u->score ? $u->score->total_sesi : 0 }} kuis</td>
                <td>
                    <a href="/admin/users/edit/{{ $u->id }}" title="Edit / Reset Password / Hapus"
                       style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;background:var(--gray-100);color:var(--gray-500);border-radius:6px">
                        <x-icon name="edit" size="14"/>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
