@extends('layouts.app-murfodat')

@section('title', 'Kelola Mufrodat')

@section('content')
@if(session('success'))
<div class="alert alert-success"><x-icon name="check-circle" size="14"/> {{ session('success') }}</div>
@endif

<form method="GET" action="/admin/mufrodat">
    <div class="btn-row" style="margin-bottom:16px;flex-wrap:wrap">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari latin atau arti..." class="form-input" style="flex:1;min-width:160px">
        <select name="kelas" class="form-select" style="width:auto" onchange="this.form.submit()">
            <option value="">Semua kelas</option>
            @foreach(['VII','VIII','IX'] as $k)
            <option value="{{ $k }}" {{ $kelas === $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
            @endforeach
        </select>
        <a href="/admin/mufrodat/tambah" class="btn btn-primary" style="white-space:nowrap">+ Tambah mufrodat</a>
    </div>
</form>

<div class="card responsive-table" style="padding:0">
    <table class="tbl">
        <thead>
            <tr style="background:var(--green-light)">
                <th>Kata Arab</th><th>Transliterasi</th><th>Arti</th><th>Bab</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mufrodat as $m)
            <tr>
                <td data-label="Kata Arab">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
                        <span style="direction:rtl;font-size:18px;flex:1">{{ $m->arab }}</span>
                        <button type="button"
                                onclick="{{ $m->audio ? "new Audio('".asset('storage/'.$m->audio)."').play()" : "bacaKata(".json_encode($m->arab).")" }}"
                                title="Dengarkan pelafalan"
                                style="width:24px;height:24px;border-radius:50%;border:1px solid var(--gray-200);background:white;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--green-dark);flex-shrink:0">
                            <x-icon name="volume" size="12"/>
                        </button>
                    </div>
                </td>
                <td data-label="Transliterasi">{{ $m->latin }}</td>
                <td data-label="Arti">
                    {{ $m->arti }}
                    @if($m->jenis)
                    <div style="font-size:11px;color:var(--gray-400);margin-top:2px">{{ $m->jenis }}</div>
                    @endif
                </td>
                <td data-label="Bab">
                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                        <span>Bab {{ $m->bab }} - {{ config('bab_judul.'.$m->kelas.'.'.$m->bab) }}</span>
                        <span class="pill {{ $m->kelas === 'VII' ? 'pill-green' : ($m->kelas === 'VIII' ? 'pill-blue' : 'pill-amber') }}">{{ $m->kelas }}</span>
                    </div>
                </td>
                <td data-label="Aksi" style="white-space:nowrap">
                    <div class="btn-row" style="gap:6px;flex-wrap:nowrap">
                        <a href="/admin/mufrodat/edit/{{ $m->id }}" title="Edit"
                           style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:var(--green);color:white;border-radius:6px;text-decoration:none;flex-shrink:0">
                            <x-icon name="edit" size="14"/>
                        </a>
                        <form method="POST" action="/admin/mufrodat/hapus/{{ $m->id }}" onsubmit="return confirm('Hapus mufrodat ini?')">
                            @csrf
                            <button type="submit" title="Hapus"
                                    style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:var(--red);color:white;border-radius:6px;border:none;cursor:pointer;flex-shrink:0">
                                <x-icon name="x" size="14"/>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination">{{ $mufrodat->withQueryString()->links('pagination.custom') }}</div>

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
