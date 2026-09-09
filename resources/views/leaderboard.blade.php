@extends('layouts.app-murfodat')

@section('title', 'Leaderboard')

@section('content')
@php $kelasUser = Auth::user()->kelas ?? 'VII'; @endphp
<div class="filter-row" style="margin:0 0 10px">
    <a href="{{ request()->fullUrlWithQuery(['kelas' => $kelasUser]) }}" class="chip {{ $kelas === $kelasUser ? 'active' : '' }}">Kelas {{ $kelasUser }}</a>
    <a href="{{ request()->fullUrlWithQuery(['kelas' => '']) }}" class="chip {{ $kelas === '' ? 'active' : '' }}">Seluruh sekolah</a>
</div>
<div class="page-sub" style="margin-bottom:20px">
    <x-icon name="refresh"/> Peringkat dihitung dari akumulasi total poin di riwayat kuis dan diperbarui real-time. Pencarian mufrodat tidak memengaruhi poin maupun peringkat.
</div>

@php $top3 = $ranking->take(3); @endphp
@if($top3->count() >= 2)
<div class="podium-row">
    @php
        $order = $top3->count() >= 3 ? [1, 0, 2] : [1, 0];
        $heights = [0 => 110, 1 => 80, 2 => 60];
        $colors = [0 => 'podium-gold', 1 => 'podium-silver', 2 => 'podium-bronze'];
    @endphp
    @foreach($order as $idx)
        @continue(!isset($top3[$idx]))
        @php $p = $top3[$idx]; @endphp
        <div class="podium-col">
            <div class="avatar" style="width:44px;height:44px;font-size:15px;margin:0 auto 6px">{{ strtoupper(substr($p->name ?? 'U', 0, 2)) }}</div>
            <div style="font-size:12px;font-weight:600;color:var(--gray-900);text-align:center">{{ explode(' ', $p->name ?? 'Siswa')[0] }}{{ $p->id === Auth::id() ? ' (kamu)' : '' }}</div>
            <div style="font-size:11px;color:var(--gray-400);text-align:center;margin-bottom:8px">{{ number_format($p->total_poin) }} poin</div>
            <div class="podium-block {{ $colors[$idx] }}" style="height:{{ $heights[$idx] }}px">{{ $idx + 1 }}</div>
        </div>
    @endforeach
</div>
@endif

@if($myRank)
<div class="card" style="background:var(--green-light);border-color:#9BD1B3;margin-bottom:20px">
    <div class="btn-row" style="justify-content:space-between">
        <div>
            <div style="font-size:11px;color:var(--gray-500)">Posisi kamu</div>
            <div style="font-size:20px;font-weight:800;color:var(--green)">#{{ $myRank->peringkat }}</div>
        </div>
        <div style="text-align:right">
            <div style="font-size:11px;color:var(--gray-500)">Total poin</div>
            <div style="font-size:20px;font-weight:800;color:var(--green)">{{ number_format($myRank->total_poin) }}</div>
        </div>
        <div style="text-align:right">
            <div style="font-size:11px;color:var(--gray-500)">Sesi kuis</div>
            <div style="font-size:20px;font-weight:800;color:var(--green)">{{ $myRank->total_sesi }}</div>
        </div>
    </div>
</div>
@endif

@if($ranking->count() > 0)
    @foreach($ranking as $item)
    @php
        $isMe = $item->id === Auth::id();
        $inisial = strtoupper(substr($item->name ?? 'U', 0, 2));
    @endphp
    <div class="lb-item {{ $isMe ? 'me' : ($item->peringkat === 1 ? 'gold' : '') }}">
        <div class="rank {{ $item->peringkat === 1 ? 'rank-1' : ($item->peringkat === 2 ? 'rank-2' : ($item->peringkat === 3 ? 'rank-3' : 'rank-n')) }}">
            @if($item->peringkat === 1) <x-icon name="trophy" size="16"/>
            @elseif($item->peringkat === 2) <x-icon name="medal" size="16"/>
            @elseif($item->peringkat === 3) <x-icon name="medal" size="16"/>
            @else {{ $item->peringkat }}
            @endif
        </div>
        <div class="avatar">{{ $inisial }}</div>
        <div style="flex:1">
            <div style="font-size:14px;font-weight:600;color:var(--gray-900)">
                {{ $item->name ?? 'Unknown' }}
                @if($isMe)<span class="pill pill-green" style="margin-left:6px">kamu</span>@endif
            </div>
            <div style="font-size:11px;color:var(--gray-400)">Level {{ $item->kelas ?? '-' }} · {{ $item->total_sesi }} kuis selesai</div>
        </div>
        <div style="text-align:right">
            <div style="font-size:16px;font-weight:700;color:var(--green)">{{ number_format($item->total_poin) }}</div>
            <div style="font-size:11px;color:var(--gray-400)">poin</div>
        </div>
    </div>
    @endforeach
@else
<div style="text-align:center;padding:48px 0;color:var(--gray-400)">
    <div style="margin-bottom:8px"><x-icon name="trophy" size="36"/></div>
    <div style="font-size:14px">Belum ada data. Mulai kuis untuk masuk leaderboard!</div>
    <a href="/kuis" class="btn btn-primary" style="margin-top:14px;display:inline-flex">Mulai kuis</a>
</div>
@endif
@endsection
