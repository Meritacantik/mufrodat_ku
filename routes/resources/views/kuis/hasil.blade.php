@extends('layouts.app-murfodat')

@section('title', 'Hasil Kuis')

@section('content')
@php
    $maxSkor = $quizSession->total_soal * 10;
    $persenRing = $maxSkor > 0 ? min(100, round(($quizSession->total_skor / $maxSkor) * 100)) : 0;
    $r = 50; $circ = 2 * pi() * $r;
    $offset = $circ * (1 - $persenRing / 100);
@endphp

<div style="text-align:center;margin-bottom:24px">
    <svg width="130" height="130" viewBox="0 0 130 130" style="margin-bottom:8px">
        <circle cx="65" cy="65" r="{{ $r }}" fill="none" stroke="var(--gray-100)" stroke-width="10"/>
        <circle cx="65" cy="65" r="{{ $r }}" fill="none" stroke="var(--amber)" stroke-width="10"
                stroke-linecap="round" stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $offset }}"
                transform="rotate(-90 65 65)"/>
        <text x="65" y="62" text-anchor="middle" font-size="26" font-weight="800" fill="var(--gray-900)">{{ $quizSession->total_skor }}</text>
        <text x="65" y="80" text-anchor="middle" font-size="11" fill="var(--gray-400)">total poin</text>
    </svg>
    <div class="page-title" style="text-align:center">Kerja bagus, {{ $quizSession->user->name ?? 'Siswa' }}!</div>
    <div class="page-sub" style="text-align:center">
        Bab {{ $quizSession->bab }} - {{ config('bab_judul.'.$quizSession->kelas.'.'.$quizSession->bab) }} . {{ $quizSession->total_soal }} soal selesai dikerjakan
    </div>
</div>

<div class="stat-row">
    <div class="stat-c stat-green">
        <div class="stat-label"><x-icon name="check-circle" size="12"/> Benar (d=0)</div>
        <div class="stat-val">{{ $quizSession->total_benar }}</div>
    </div>
    <div class="stat-c stat-amber">
        <div class="stat-label"><x-icon name="alert-triangle" size="12"/> Typo (1≤d≤2)</div>
        <div class="stat-val">{{ $quizSession->total_typo }}</div>
    </div>
    <div class="stat-c stat-red">
        <div class="stat-label"><x-icon name="x-circle" size="12"/> Salah (d&gt;2)</div>
        <div class="stat-val">{{ $quizSession->total_salah }}</div>
    </div>
    <div class="stat-c stat-white">
        <div class="stat-label"><x-icon name="trophy" size="12"/> Total poin</div>
        <div class="stat-val" style="color:var(--gray-900)">{{ $quizSession->total_skor }}</div>
    </div>
</div>

<div style="text-align:center;font-size:11.5px;color:var(--gray-400);margin:4px 0 20px">
    Skor: BENAR +10, TYPO +5, SALAH +0 &nbsp;|&nbsp;
    ({{ $quizSession->total_benar }}×10)+({{ $quizSession->total_typo }}×5)+({{ $quizSession->total_salah }}×0) = {{ $quizSession->total_skor }}
</div>

<div class="card" style="margin-bottom:20px">
    <div class="card-title">Ringkasan soal</div>
    @foreach($quizSession->answers as $answer)
    <div class="list-item" style="justify-content:space-between">
        <div style="font-size:13px">
            <b>{{ $answer->jawaban_referensi }}</b>
            <span style="color:var(--gray-400)"> → </span>
            <span style="direction:rtl;display:inline-block">{{ $answer->mufrodat->arab }}</span>
        </div>
        <span class="pill {{ $answer->status === 'BENAR' ? 'pill-green' : ($answer->status === 'TYPO' ? 'pill-amber' : 'pill-red') }}">
            {{ ucfirst(strtolower($answer->status)) }} (d={{ $answer->jarak_levenshtein }}) · +{{ $answer->poin }}
        </span>
    </div>
    @endforeach
</div>

<div class="btn-row">
    <a href="/kuis" class="btn btn-secondary">Ulangi kuis</a>
    <a href="/leaderboard" class="btn btn-primary">Lihat leaderboard</a>
    <a href="/dashboard" class="btn btn-primary">Kembali ke dashboard</a>
</div>
@endsection
