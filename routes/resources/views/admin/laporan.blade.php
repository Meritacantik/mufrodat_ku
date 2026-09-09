@extends('layouts.app-murfodat')

@section('title', 'Laporan Akurasi Sistem')

@section('content')
<div class="btn-row" style="justify-content:space-between">
    <div class="page-sub" style="margin-bottom:0">Evaluasi performa algoritma Levenshtein Distance (BAB II - 2.10 &amp; 2.11)</div>
    <a href="{{ route('admin.laporan.ekspor') }}" class="btn btn-secondary"><x-icon name="download" size="14"/> Ekspor CSV</a>
</div>

<div class="stat-row" style="margin-top:16px">
    <div class="stat-c stat-white">
        <div class="stat-label">Siswa aktif</div>
        <div class="stat-val" style="color:var(--gray-900)">{{ $siswaAktif }}</div>
    </div>
    <div class="stat-c stat-green">
        <div class="stat-label">Kuis dikerjakan</div>
        <div class="stat-val">{{ $kuisDikerjakan }}</div>
    </div>
    <div class="stat-c stat-amber">
        <div class="stat-label">Rata-rata skor</div>
        <div class="stat-val">{{ $rataRataSkor }}%</div>
    </div>
    <div class="stat-c stat-red">
        <div class="stat-label">Butuh perhatian</div>
        <div class="stat-val">{{ $butuhPerhatian }} siswa</div>
    </div>
</div>

<div class="card" style="margin-bottom:20px">
    <form method="GET" class="btn-row" style="margin-bottom:14px">
        <input type="text" name="cari" value="{{ $search }}" placeholder="Cari siswa atau bab..." class="form-input" style="flex:1">
        <button type="submit" class="btn btn-secondary">Cari</button>
    </form>
    <table class="tbl">
        <thead>
            <tr><th>Siswa</th><th>Bab</th><th>Skor</th><th>Waktu</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($sesiKuis as $s)
            <tr>
                <td>{{ $s->user->name ?? '-' }}</td>
                <td>Bab {{ $s->bab }} - {{ $s->judul_bab }}</td>
                <td>{{ $s->skor_persen }}%</td>
                <td style="color:var(--gray-400);font-size:11px">{{ $s->created_at->diffForHumans() }}</td>
                <td>
                    @php
                        $warnaStatus = ['Selesai' => 'pill-green', 'Belum selesai' => 'pill-amber', 'Perlu remedial' => 'pill-red'];
                    @endphp
                    <span class="pill {{ $warnaStatus[$s->status_laporan] }}">{{ $s->status_laporan }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--gray-400)">Belum ada data sesi kuis</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin:28px 0 4px">
    <div class="page-title" style="font-size:16px;margin-bottom:2px">Distribusi Hasil Kuis Siswa</div>
    <div class="page-sub" style="margin-bottom:0">Data real-time dari jawaban kuis yang sudah dikerjakan siswa (bukan pengujian algoritma)</div>
</div>

<div class="stat-row">
    <div class="stat-c stat-white">
        <div class="stat-label">Total jawaban kuis</div>
        <div class="stat-val" style="color:var(--gray-900)">{{ $totalJawaban }}</div>
        <div class="stat-sub">seluruh siswa</div>
    </div>
    <div class="stat-c stat-green">
        <div class="stat-label"><x-icon name="check-circle" size="12"/> BENAR</div>
        <div class="stat-val">{{ $benar }}</div>
    </div>
    <div class="stat-c stat-amber">
        <div class="stat-label"><x-icon name="alert-triangle" size="12"/> TYPO</div>
        <div class="stat-val">{{ $typo }}</div>
    </div>
    <div class="stat-c stat-red">
        <div class="stat-label"><x-icon name="x-circle" size="12"/> SALAH</div>
        <div class="stat-val">{{ $salah }}</div>
    </div>
</div>

<div class="card" style="margin-top:16px">
    <div class="card-title"><x-icon name="chart-bar"/> Persentase distribusi jawaban siswa</div>
    @if($totalJawaban > 0)
        @foreach([['BENAR', $benar, 'var(--green)', 'fill-green'], ['TYPO', $typo, 'var(--amber)', 'fill-amber'], ['SALAH', $salah, 'var(--red)', 'fill-red']] as [$label, $jml, $color, $fillClass])
        <div class="progress-wrap">
            <div class="progress-meta"><span style="color:{{ $color }}">{{ $label }}</span><span>{{ round(($jml/$totalJawaban)*100) }}%</span></div>
            <div class="progress-bar"><div class="progress-fill {{ $fillClass }}" style="width:{{ round(($jml/$totalJawaban)*100) }}%"></div></div>
        </div>
        @endforeach
    @else
        <div style="text-align:center;color:var(--gray-400);padding:20px;font-size:13px">Belum ada data jawaban kuis</div>
    @endif
</div>

<div style="margin:32px 0 4px">
    <div class="page-title" style="font-size:16px;margin-bottom:2px">Evaluasi Akurasi Algoritma</div>
    <div class="page-sub" style="margin-bottom:0">
        Confusion matrix one-vs-rest terhadap {{ $evaluasiAlgoritma['total'] }} data uji variasi typo dengan ground truth diketahui (Bab III &amp; Bab II - 2.10/2.11)
    </div>
</div>

<div class="grid2">
    <div class="card">
        <div class="card-title"><x-icon name="target"/> Metrik evaluasi algoritma (data uji)</div>
        @foreach([
            ['Accuracy', 'Klasifikasi tepat / Total data uji', $evaluasiAlgoritma['accuracy'], 'fill-green', 'var(--green)'],
            ['Precision', 'Macro-average TP/(TP+FP), 3 kelas', $evaluasiAlgoritma['precision'], 'fill-blue', 'var(--gray-700)'],
            ['Recall', 'Macro-average TP/(TP+FN), 3 kelas', $evaluasiAlgoritma['recall'], 'fill-amber', 'var(--amber)'],
            ['Error Rate', '1 - Accuracy', $evaluasiAlgoritma['error_rate'], 'fill-red', 'var(--red)'],
        ] as [$label, $rumus, $nilai, $fillClass, $color])
        <div class="list-item" style="justify-content:space-between">
            <div style="flex:1">
                <div style="font-weight:600;font-size:13px">{{ $label }}</div>
                <div style="font-size:11px;color:var(--gray-400);margin-bottom:4px">{{ $rumus }}</div>
                <div class="progress-bar" style="width:140px"><div class="progress-fill {{ $fillClass }}" style="width:{{ $nilai }}%"></div></div>
            </div>
            <div style="font-size:20px;font-weight:800;color:{{ $color }}">{{ $nilai }}%</div>
        </div>
        @endforeach
        <div class="alert {{ $evaluasiAlgoritma['accuracy'] >= 80 ? 'alert-success' : 'alert-error' }}" style="margin-top:14px">
            @if($evaluasiAlgoritma['accuracy'] >= 80)
                <x-icon name="check-circle" size="13"/> Algoritma memenuhi target akurasi &ge; 80%
            @else
                <x-icon name="alert-triangle" size="13"/> Akurasi algoritma belum mencapai target 80%
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-title"><x-icon name="grid"/> Confusion matrix (baris = seharusnya, kolom = prediksi)</div>
        <table class="tbl">
            <thead>
                <tr><th>Aktual \ Prediksi</th><th style="text-align:center">BENAR</th><th style="text-align:center">TYPO</th><th style="text-align:center">SALAH</th></tr>
            </thead>
            <tbody>
                @foreach(['BENAR','TYPO','SALAH'] as $aktual)
                <tr>
                    <td style="font-weight:600">{{ $aktual }}</td>
                    @foreach(['BENAR','TYPO','SALAH'] as $prediksi)
                    <td style="text-align:center; {{ $aktual === $prediksi ? 'font-weight:800;color:var(--green)' : '' }}">
                        {{ $evaluasiAlgoritma['confusion'][$aktual][$prediksi] }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card-title" style="margin-top:16px"><x-icon name="ruler"/> Per kelas (one-vs-rest)</div>
        <table class="tbl">
            <thead>
                <tr><th>Kelas</th><th style="text-align:center">TP</th><th style="text-align:center">FP</th><th style="text-align:center">FN</th><th style="text-align:center">TN</th><th style="text-align:center">Precision</th><th style="text-align:center">Recall</th></tr>
            </thead>
            <tbody>
                @foreach($evaluasiAlgoritma['per_kelas'] as $kelas => $m)
                <tr>
                    <td style="font-weight:600">{{ $kelas }}</td>
                    <td style="text-align:center">{{ $m['tp'] }}</td>
                    <td style="text-align:center">{{ $m['fp'] }}</td>
                    <td style="text-align:center">{{ $m['fn'] }}</td>
                    <td style="text-align:center">{{ $m['tn'] }}</td>
                    <td style="text-align:center">{{ $m['precision'] }}%</td>
                    <td style="text-align:center">{{ $m['recall'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@php $kasusMeleset = collect($evaluasiAlgoritma['detail'])->reject(fn($d) => $d['cocok']); @endphp
@if($kasusMeleset->isNotEmpty())
<div class="card" style="margin-top:20px">
    <div class="card-title"><x-icon name="alert-triangle"/> Kasus data uji yang meleset dari ekspektasi ({{ $kasusMeleset->count() }})</div>
    <table class="tbl">
        <thead>
            <tr><th>Jawaban uji</th><th>Kata asli</th><th>Jenis variasi</th><th>Seharusnya</th><th>Prediksi sistem</th><th style="text-align:center">Jarak</th></tr>
        </thead>
        <tbody>
            @foreach($kasusMeleset as $d)
            <tr>
                <td>{{ $d['jawaban_uji'] }}</td>
                <td>{{ $d['kata_asli'] }}</td>
                <td>{{ $d['jenis_variasi'] }}</td>
                <td><span class="pill pill-green">{{ $d['status_seharusnya'] }}</span></td>
                <td><span class="pill pill-red">{{ $d['status_prediksi'] }}</span></td>
                <td style="text-align:center">{{ $d['jarak'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="card" id="log-jawaban">
    <div class="card-title"><x-icon name="list"/> Log jawaban kuis terbaru</div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Siswa</th><th>Soal (Arab)</th><th>Jawaban siswa</th><th>Referensi</th>
                <th style="text-align:center">Jarak (d)</th><th>Status</th><th style="text-align:center">Poin</th><th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logJawaban as $log)
            <tr>
                <td>{{ $log->session->user->name ?? '-' }}</td>
                <td style="font-size:16px;direction:rtl">{{ $log->mufrodat->arab ?? '-' }}</td>
                <td>{{ $log->jawaban_siswa ?: '-' }}</td>
                <td style="color:var(--gray-500)">{{ $log->jawaban_referensi }}</td>
                <td style="text-align:center;font-weight:700;color:{{ $log->status === 'BENAR' ? 'var(--green)' : ($log->status === 'TYPO' ? 'var(--amber)' : 'var(--red)') }}">
                    {{ $log->jarak_levenshtein }}
                </td>
                <td><span class="pill {{ $log->status === 'BENAR' ? 'pill-green' : ($log->status === 'TYPO' ? 'pill-amber' : 'pill-red') }}">{{ $log->status }}</span></td>
                <td style="text-align:center;font-weight:700;color:{{ $log->status === 'BENAR' ? 'var(--green)' : ($log->status === 'TYPO' ? 'var(--amber)' : 'var(--red)') }}">+{{ $log->poin }}</td>
                <td style="color:var(--gray-400);font-size:11px">{{ $log->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $logJawaban->withQueryString()->links('pagination.custom') }}</div>
</div>

@if($logJawaban->currentPage() > 1)
<script>
    document.getElementById('log-jawaban')?.scrollIntoView({ behavior: 'instant', block: 'start' });
</script>
@endif
@endsection
