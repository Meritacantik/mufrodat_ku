@extends('layouts.app-murfodat')

@section('title', 'Pilih Kategori Kuis')

@section('content')
<div class="page-sub">Pilih bab yang ingin kamu kerjakan.</div>

<div class="card" style="margin:14px 0 20px;background:linear-gradient(135deg,var(--amber),#c9a15a);color:white;border:none">
    <div class="btn-row" style="justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:11px;opacity:.85;margin-bottom:2px">Pangkat kamu sekarang</div>
            <div style="font-size:20px;font-weight:800;display:flex;align-items:center;gap:8px">
                <x-icon name="{{ $pangkat['icon'] }}" size="20"/> {{ $pangkat['nama'] }}
            </div>
        </div>
        <div style="text-align:right">
            @if($pangkat['sudah_maksimal'])
                <div style="font-size:12px;opacity:.9">Pangkat tertinggi tercapai! <x-icon name="sparkles" size="13"/></div>
            @else
                <div style="font-size:11px;opacity:.85">{{ $pangkat['persen_selesai'] }}% menuju</div>
                <div style="font-size:13px;font-weight:700">{{ $pangkat['pangkat_berikutnya'] }} (butuh {{ $pangkat['sisa_persen'] }}% lagi)</div>
            @endif
        </div>
    </div>
</div>

@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="grid3" style="margin-top:16px">
    @php $warnaCard = ['stat-green', 'stat-red', 'stat-amber']; @endphp
    @forelse($babs as $i => $b)
    @if($b->terkunci)
        <div class="stat-c stat-white" style="border:1px solid var(--gray-200);opacity:.6;cursor:not-allowed">
            <div class="btn-row" style="justify-content:space-between;margin-bottom:4px">
                <span style="font-size:10px;color:var(--gray-400)">Bab {{ $b->bab }}</span>
                <span><x-icon name="lock" size="13"/></span>
            </div>
            <div style="font-weight:700;font-size:14px;margin-bottom:10px;color:var(--gray-500)">{{ $b->judul }}</div>
            <div style="font-size:11px;color:var(--gray-400)">Selesaikan bab sebelumnya untuk membuka</div>
        </div>
    @else
    <form method="POST" action="/kuis/mulai">
        @csrf
        <input type="hidden" name="kelas" value="{{ $kelasUser }}">
        <input type="hidden" name="bab" value="{{ $b->bab }}">
        @if($b->soal_dikerjakan === 0)
        <button type="submit" class="stat-c stat-white" style="border:1px solid var(--gray-200);cursor:pointer;text-align:left;width:100%;font-family:inherit;opacity:.75">
            <div style="font-size:10px;color:var(--gray-400);margin-bottom:4px">Bab {{ $b->bab }}</div>
            <div style="font-weight:700;font-size:14px;margin-bottom:10px;color:var(--gray-700)">{{ $b->judul }}</div>
            <div class="btn-row" style="justify-content:space-between;margin-bottom:6px">
                <span style="font-size:11px;color:var(--gray-400)">{{ $b->soal_dikerjakan }}/{{ $b->total_soal }} soal</span>
                <span style="font-size:11px;color:var(--gray-400)">{{ $b->persen }}%</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width:{{ $b->persen }}%"></div></div>
        </button>
        @else
        <button type="submit" class="stat-c {{ $warnaCard[$i % 3] }}" style="border:none;cursor:pointer;text-align:left;width:100%;font-family:inherit">
            <div style="font-size:10px;opacity:.85;margin-bottom:4px">Bab {{ $b->bab }}</div>
            <div style="font-weight:700;font-size:14px;margin-bottom:10px">{{ $b->judul }}</div>
            <div class="btn-row" style="justify-content:space-between;margin-bottom:6px">
                <span style="font-size:11px">{{ $b->soal_dikerjakan }}/{{ $b->total_soal }} soal</span>
                <span style="font-size:11px">{{ $b->persen }}%</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width:{{ $b->persen }}%;background:white"></div></div>
        </button>
        @endif
    </form>
    @endif
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:48px 0;color:var(--gray-400)">
        Belum ada bab kuis yang aktif untuk Kelas {{ $kelasUser }}.
    </div>
    @endforelse
</div>

<script>
    // Cegah "Mulai Kuis" ke-klik 2x (double click / koneksi lambat lalu
    // diklik ulang) yang bisa bikin 2 sesi kuis dobel. Cuma nonaktifkan
    // tombol pas disubmit, tidak mengubah proses submit form itu sendiri.
    document.querySelectorAll('form[action="/kuis/mulai"]').forEach(function (form) {
        form.addEventListener('submit', function () {
            this.querySelectorAll('button[type="submit"]').forEach(function (btn) {
                btn.disabled = true;
            });
        });
    });
</script>
@endsection
