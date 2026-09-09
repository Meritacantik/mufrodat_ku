@extends('layouts.app-murfodat')

@section('title', 'Dashboard Admin')

@section('content')
<div class="stat-row" style="margin-top:4px">
    <div class="stat-c stat-white">
        <div class="stat-top"><div class="stat-icon"><x-icon name="book" size="20"/></div></div>
        <div class="stat-val">{{ $totalMufrodat }}</div>
        <div class="stat-label">Total mufrodat</div>
        <div class="stat-sub">3 kelas</div>
    </div>
    <div class="stat-c stat-green">
        <div class="stat-top"><div class="stat-icon"><x-icon name="users" size="20"/></div></div>
        <div class="stat-val">{{ $totalSiswa }}</div>
        <div class="stat-label">Total siswa terdaftar</div>
    </div>
    <div class="stat-c stat-amber">
        <div class="stat-top"><div class="stat-icon"><x-icon name="check-circle" size="20"/></div></div>
        <div class="stat-val">{{ $totalSesi }}</div>
        <div class="stat-label">Sesi kuis</div>
    </div>
    <div class="stat-c stat-red">
        <div class="stat-top"><div class="stat-icon"><x-icon name="target" size="20"/></div></div>
        <div class="stat-val">{{ $accuracy }}%</div>
        <div class="stat-label">Akurasi sistem</div>
        <div class="stat-sub">dari {{ $totalJawaban }} jawaban</div>
    </div>
</div>

<div class="grid2" style="margin-top:20px">
    <div class="card">
        <div class="card-title"><x-icon name="list"/> Sesi kuis terbaru</div>
        <table class="tbl">
            <tr><th>Siswa</th><th>Kelas</th><th>Bab</th><th>Skor</th><th>Waktu</th></tr>
            @foreach($sesiTerbaru as $s)
            <tr>
                <td>{{ $s->user->name ?? '-' }}</td>
                <td><span class="pill pill-blue">{{ $s->kelas }}</span></td>
                <td>Bab {{ $s->bab }}</td>
                <td><b style="color:var(--green)">+{{ $s->total_skor }}</b></td>
                <td style="color:var(--gray-400)">{{ $s->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="card">
        <div class="card-title"><x-icon name="target"/> Performa algoritma</div>
        <div class="progress-wrap">
            <div class="progress-meta"><span>Akurasi sistem</span><span style="font-weight:600;color:var(--green)">{{ $accuracy }}%</span></div>
            <div class="progress-bar"><div class="progress-fill fill-green" style="width:{{ $accuracy }}%"></div></div>
        </div>
        <div style="font-size:12px;color:var(--gray-500);line-height:1.8;margin-top:12px">
            <div>Total jawaban: <b>{{ $totalJawaban }}</b></div>
            <div>Target akurasi: <b style="color:var(--green)">≥ 80%</b></div>
            <div class="alert alert-success" style="margin-top:10px">
                @if($accuracy >= 80) <x-icon name="check-circle" size="13"/> Sistem memenuhi target akurasi
                @else <x-icon name="alert-triangle" size="13"/> Akurasi belum mencapai target 80%
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
