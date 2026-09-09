@extends('layouts.app-murfodat')

@section('title', 'Hasil Jawaban')

@section('content')
<div class="quiz-card">
    <div class="page-sub">Soal {{ $hasil['index'] }} dari {{ $hasil['total'] }}</div>

    @if($hasil['status'] === 'BENAR')
        <div style="margin:10px 0 6px"><x-icon name="check-circle" size="44"/></div>
        <div style="font-size:20px;font-weight:700;color:var(--green);margin-bottom:4px">BENAR!</div>
        <div class="page-sub">+{{ $hasil['poin'] }} poin</div>
    @elseif($hasil['status'] === 'TYPO')
        <div style="margin:10px 0 6px"><x-icon name="alert-triangle" size="44"/></div>
        <div style="font-size:20px;font-weight:700;color:var(--amber);margin-bottom:4px">HAMPIR BENAR (TYPO)</div>
        <div class="page-sub">Jarak edit: {{ $hasil['jarak'] }} karakter · +{{ $hasil['poin'] }} poin</div>
    @else
        <div style="margin:10px 0 6px"><x-icon name="x-circle" size="44"/></div>
        <div style="font-size:20px;font-weight:700;color:var(--red);margin-bottom:4px">SALAH</div>
        <div class="page-sub">Jarak edit: {{ $hasil['jarak'] }} karakter · +{{ $hasil['poin'] }} poin</div>
    @endif

    <div class="quiz-arabic" style="margin-top:16px">{{ $hasil['arab'] }}</div>
    <button class="btn btn-ghost" style="margin-bottom:12px" onclick="bacaArab()"><x-icon name="volume" size="14"/> Dengarkan pelafalan</button>
    <div style="font-size:14px;color:var(--gray-500);margin-bottom:4px">{{ $hasil['latin'] }}</div>
    <div style="font-size:15px;font-weight:600;color:var(--green);margin-bottom:18px">{{ $hasil['arti'] }}</div>

    <div class="card" style="text-align:left;font-size:13px;color:var(--gray-600);margin-bottom:18px">
        <div>Jawabanmu: <b style="color:var(--gray-900)">{{ $hasil['jawaban'] ?: '-' }}</b></div>
        @if($hasil['status'] !== 'BENAR')
        <div style="margin-top:4px">Jawaban benar: <span style="color:var(--green);font-weight:600">{{ $hasil['latin'] }}</span></div>
        @endif
    </div>

    @if($hasil['index'] >= $hasil['total'])
        <a href="/kuis/hasil" class="btn btn-secondary btn-full">Lihat hasil akhir <x-icon name="chevron-right" size="13"/></a>
    @else
        <a href="/kuis/soal" class="btn btn-primary btn-full">Soal berikutnya <x-icon name="chevron-right" size="13"/></a>
    @endif
</div>

<script>
    function bacaArab() {
        const arab = @json($hasil['arab']);
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(arab);
            utterance.lang = 'ar-SA';
            utterance.rate = 0.8;
            const voices = window.speechSynthesis.getVoices();
            const arabVoice = voices.find(v => v.lang.startsWith('ar'));
            if (arabVoice) utterance.voice = arabVoice;
            window.speechSynthesis.speak(utterance);
        }
    }
    window.speechSynthesis.onvoiceschanged = function() {};
</script>
@endsection
