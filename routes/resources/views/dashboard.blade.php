@extends('layouts.app-murfodat')

@section('title', 'Dashboard')

@section('content')
<div class="page-title">Selamat Datang {{ $user->name }} <x-icon name="wave" size="20"/></div>
<div class="page-sub">Hari ini adalah waktu yang tepat untuk belajar mufrodat baru.</div>

<div class="stat-row">
    <div class="stat-c stat-amber">
        <div class="stat-top"><div class="stat-icon"><x-icon name="medal" size="20"/></div></div>
        <div class="stat-val">{{ number_format($totalPoin) }}</div>
        <div class="stat-label">Total poin</div>
        <div class="stat-sub">+{{ number_format($poinHariIni) }} hari ini</div>
    </div>
    <div class="stat-c stat-green">
        <div class="stat-top"><div class="stat-icon"><x-icon name="trophy" size="20"/></div></div>
        <div class="stat-val">{{ $totalSesi > 0 ? '#'.$peringkat : '-' }}</div>
        <div class="stat-label">Peringkat</div>
        <div class="stat-sub">dari {{ $totalSiswaKelas }} siswa</div>
    </div>
    <div class="stat-c stat-white">
        <div class="stat-top"><div class="stat-icon"><x-icon name="check-circle" size="20"/></div></div>
        <div class="stat-val">{{ $totalSesi }}</div>
        <div class="stat-label">Kuis selesai</div>
        <div class="stat-sub">Kuis sesi</div>
    </div>
    <div class="stat-c stat-red">
        <div class="stat-top"><div class="stat-icon"><x-icon name="book" size="20"/></div></div>
        <div class="stat-val">{{ $totalDikuasai }}</div>
        <div class="stat-label">Kata dikuasai</div>
        <div class="stat-sub">dari {{ $totalMufrodat }} kata</div>
    </div>
</div>

<div class="btn-row" style="justify-content:space-between;margin-bottom:10px">
    <div class="card-title" style="margin-bottom:0">Lanjutkan belajar</div>
    <a href="{{ route('kuis.pilih') }}" style="font-size:12px;color:var(--green-dark);font-weight:600;text-decoration:none">Lihat semua bab →</a>
</div>

<div class="grid3" style="margin-bottom:20px">
    @php $warnaCard = ['stat-green', 'stat-red', 'stat-amber']; @endphp
    @foreach($lanjutkanBelajar as $i => $b)
    <a href="{{ route('kuis.pilih') }}" class="stat-c {{ $warnaCard[$i % 3] }}" style="text-decoration:none;display:block">
        <div style="font-size:10px;opacity:.85;margin-bottom:4px">Bab {{ $b->bab }}</div>
        <div style="font-weight:700;font-size:14px;margin-bottom:10px">{{ $b->judul }}</div>
        <div class="btn-row" style="justify-content:space-between;margin-bottom:6px">
            <span style="font-size:11px">{{ $b->soal_dikerjakan }}/{{ $b->total_soal }} soal</span>
            <span style="font-size:11px">{{ $b->persen }}%</span>
        </div>
        <div class="progress-bar"><div class="progress-fill" style="width:{{ $b->persen }}%;background:white"></div></div>
    </a>
    @endforeach
</div>

<div class="card">
    <div class="card-title"><x-icon name="clock"/> Aktivitas terbaru</div>
    @if($aktivitas->count() > 0)
        @foreach($aktivitas as $a)
        <div class="list-item" style="justify-content:space-between">
            <div>
                <div style="font-weight:600;font-size:13px;color:var(--gray-900)">{{ $a->deskripsi }}</div>
                <div style="font-size:11px;color:var(--gray-400)">{{ $a->created_at->diffForHumans() }}</div>
            </div>
            @if($a->tipe === 'kuis')
            <span class="pill pill-green">+{{ $a->poin }} poin</span>
            @endif
        </div>
        @endforeach
    @else
        <div style="text-align:center;color:var(--gray-400);font-size:13px;padding:20px 0">
            Belum ada aktivitas.<br>Mulai kuis pertamamu!
        </div>
    @endif
</div>
@endsection
