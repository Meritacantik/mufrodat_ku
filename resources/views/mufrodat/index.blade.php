@extends('layouts.app-murfodat')

@section('title', 'Cari Kata')

@section('content')
<form method="GET" action="/mufrodat">
    <div class="btn-row" style="margin-bottom:16px">
        <input type="text" name="q" value="{{ $query }}" placeholder='Cari mufrodat, misalnya "rumah" atau "بيت"...' autofocus
               class="form-input" style="flex:1">
        <button type="submit" class="btn btn-primary"><x-icon name="search" size="14"/> Cari</button>
    </div>

    <div class="filter-row" style="margin-bottom:20px">
        @foreach(['' => 'Semua', 'VII' => 'Kelas VII', 'VIII' => 'Kelas VIII', 'IX' => 'Kelas IX'] as $k => $label)
        <a href="{{ request()->fullUrlWithQuery(['kelas' => $k]) }}" class="chip {{ $kelas === $k ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
</form>

@if($query && $autocorrect && $hasil->count() > 0)
<div class="alert alert-warning" style="margin-bottom:16px">
    <x-icon name="alert-triangle"/> <span><b>Autocorrect aktif</b>, menampilkan hasil terdekat untuk "<b>{{ $query }}</b>" menggunakan Levenshtein Distance</span>
</div>
@endif

@if($query)
<div class="page-sub" style="margin-bottom:14px">{{ $hasil->count() }} mufrodat ditemukan</div>
@endif

@if($hasil->count() > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:14px">
    @foreach($hasil as $m)
    <div style="background:white;border:1px solid var(--gray-200);border-radius:12px;padding:16px;text-align:center">
        <div class="btn-row" style="justify-content:space-between;margin-bottom:10px">
            <span class="pill pill-green" style="font-size:10px">Bab {{ $m->bab }}</span>
            <button type="button"
                    onclick="{{ $m->audio ? "new Audio('".asset('storage/'.$m->audio)."').play()" : "bacaKata(".json_encode($m->arab).")" }}"
                    title="Dengarkan pelafalan"
                    style="border:1px solid var(--gray-200);background:white;border-radius:50%;width:26px;height:26px;cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center;color:var(--green-dark)"><x-icon name="volume" size="13"/></button>
        </div>
        <div style="font-size:30px;font-weight:700;color:var(--gray-900);margin-bottom:8px;font-family:'Traditional Arabic','Arial',sans-serif">{{ $m->arab }}</div>
        <div style="font-size:13px;color:var(--gray-500);margin-bottom:2px">{{ $m->latin }}</div>
        <div style="font-size:12px;color:var(--gray-400)">{{ $m->arti }}</div>
        @if($m->jenis)
        <div style="font-size:10px;color:var(--gray-300);margin-top:4px">{{ $m->jenis }}</div>
        @endif
    </div>
    @endforeach
</div>

@if(!$query && method_exists($hasil, 'links'))
<div class="btn-row" style="justify-content:center;margin-top:24px">
    @if($hasil->onFirstPage())
        <span class="btn btn-secondary" style="opacity:.4;pointer-events:none"><x-icon name="chevron-left" size="13"/> Sebelumnya</span>
    @else
        <a href="{{ $hasil->previousPageUrl() }}" class="btn btn-secondary"><x-icon name="chevron-left" size="13"/> Sebelumnya</a>
    @endif
    <span style="font-size:12px;color:var(--gray-500);align-self:center">Halaman {{ $hasil->currentPage() }} dari {{ $hasil->lastPage() }}</span>
    @if($hasil->hasMorePages())
        <a href="{{ $hasil->nextPageUrl() }}" class="btn btn-secondary">Selanjutnya <x-icon name="chevron-right" size="13"/></a>
    @else
        <span class="btn btn-secondary" style="opacity:.4;pointer-events:none">Selanjutnya <x-icon name="chevron-right" size="13"/></span>
    @endif
</div>
@endif

@elseif($query)
<div style="text-align:center;padding:48px 0">
    <div style="margin-bottom:10px"><x-icon name="search" size="36"/></div>
    <p style="color:var(--gray-500);font-size:13px">Kata "<b>{{ $query }}</b>" tidak ditemukan.</p>
    <p style="color:var(--gray-400);font-size:11px;margin-top:4px">Coba kata lain atau periksa ejaan.</p>
</div>
@endif

<script>
    function bacaKata(arab) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            let voices = window.speechSynthesis.getVoices();
            function speak() {
                voices = window.speechSynthesis.getVoices();
                const utterance = new SpeechSynthesisUtterance(arab);
                utterance.lang = 'ar-SA';
                utterance.rate = 0.7;
                const arabVoice = voices.find(v => v.lang.startsWith('ar'));
                if (arabVoice) utterance.voice = arabVoice;
                window.speechSynthesis.speak(utterance);
            }
            if (voices.length === 0) window.speechSynthesis.onvoiceschanged = speak;
            else speak();
        }
    }
</script>
@endsection
