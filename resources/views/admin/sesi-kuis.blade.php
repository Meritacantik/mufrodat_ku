@extends('layouts.app-murfodat')

@section('title', 'Kelola Sesi Kuis')

@section('content')
<div class="page-sub" style="margin-bottom:16px">Bab / kategori kuis aktif, atur bab dan kelas yang bisa dikerjakan siswa</div>

@if(session('success'))
<div class="alert alert-success"><x-icon name="check-circle" size="14"/> {{ session('success') }}</div>
@endif

<div class="card" style="padding:0">
    <table class="tbl">
        <thead>
            <tr><th>Kelas</th><th>Bab</th><th>Jumlah Soal</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($daftarBab as $b)
            <tr>
                <td><span class="pill {{ $b->kelas === 'VII' ? 'pill-green' : ($b->kelas === 'VIII' ? 'pill-blue' : 'pill-amber') }}">{{ $b->kelas }}</span></td>
                <td>Bab {{ $b->bab }} - {{ $b->judul }}</td>
                <td>{{ $b->jumlah_soal }} soal</td>
                <td>
                    @if($b->status === 'aktif')
                    <span class="pill pill-green">Aktif</span>
                    @else
                    <span class="pill" style="background:var(--gray-200);color:var(--gray-600)">Draf</span>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.sesi-kuis.toggle', ['kelas' => $b->kelas, 'bab' => $b->bab]) }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="padding:4px 12px;font-size:11px">Kelola</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
