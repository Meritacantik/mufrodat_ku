@extends('layouts.app-murfodat')

@section('title', $mufrodat ? 'Edit Mufrodat' : 'Tambah Mufrodat')

@section('content')
<div class="admin-form-wrap">
<a href="/admin/mufrodat" class="btn btn-secondary" style="display:inline-flex;padding:6px 14px;font-size:12px;margin-bottom:16px">
    <x-icon name="chevron-left" size="12"/> Kembali
</a>

<div class="card">
    <div class="card-title">Detail Kosakata</div>
    <form method="POST" action="{{ $mufrodat ? '/admin/mufrodat/update/'.$mufrodat->id : '/admin/mufrodat/simpan' }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Tulisan Arab</label>
            <textarea name="arab" placeholder="كِتَابٌ" class="form-input" style="direction:rtl;font-size:20px;height:60px">{{ old('arab', $mufrodat->arab ?? '') }}</textarea>
            @error('arab')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="grid2">
            <div class="form-group">
                <label class="form-label">Transliterasi Latin</label>
                <input type="text" name="latin" value="{{ old('latin', $mufrodat->latin ?? '') }}" placeholder="kitabun" class="form-input">
                @error('latin')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Arti / Terjemahan</label>
                <input type="text" name="arti" value="{{ old('arti', $mufrodat->arti ?? '') }}" placeholder="buku" class="form-input">
                @error('arti')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Jenis Kata</label>
            <select name="jenis" class="form-select">
                <option value="">- Pilih jenis kata -</option>
                @foreach(["Mufrad", "Fi'il Madhi", "Fi'il Mudhari'", "Fi'il Amr", "Masdar", "Mubtada - Khabar"] as $j)
                <option value="{{ $j }}" {{ old('jenis', $mufrodat->jenis ?? '') === $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
            @error('jenis')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="grid2">
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-select">
                    @foreach(['VII','VIII','IX'] as $k)
                    <option value="{{ $k }}" {{ old('kelas', $mufrodat->kelas ?? '') === $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                    @endforeach
                </select>
                @error('kelas')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Bab</label>
                <input type="number" name="bab" value="{{ old('bab', $mufrodat->bab ?? '') }}" placeholder="1" min="1" max="10" class="form-input">
                @error('bab')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="btn-row" style="margin-top:20px">
            <button type="submit" class="btn btn-primary">{!! $mufrodat ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><path d="M5 4h11l3 3v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/><path d="M8 4v5h8V4"/><path d="M8 21v-6h8v6"/></svg> Simpan perubahan' : '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><path d="M12 5v14"/><path d="M5 12h14"/></svg> Tambah mufrodat' !!}</button>
            <a href="/admin/mufrodat" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@if($mufrodat)
<div class="card" style="margin-top:16px">
    <div class="card-title"><x-icon name="volume" size="13"/> Audio Pelafalan</div>
    @if($mufrodat->audio)
    <div class="btn-row" style="margin-bottom:14px">
        <button type="button" class="btn btn-secondary" onclick="new Audio('{{ asset('storage/'.$mufrodat->audio) }}').play()">▶️ Dengarkan audio saat ini</button>
        <form method="POST" action="{{ route('admin.mufrodat.hapus-audio', $mufrodat->id) }}" onsubmit="return confirm('Hapus audio kata ini? Mufrodat-nya tidak akan ikut terhapus.')">
            @csrf
            <button type="submit" class="btn btn-secondary" style="color:var(--red);border-color:var(--red)">
                <x-icon name="x" size="12"/> Hapus audio
            </button>
        </form>
    </div>
    @else
    <div style="font-size:12px;color:var(--gray-400);margin-bottom:14px">Belum ada audio untuk kata ini.</div>
    @endif
    <form method="POST" action="{{ route('admin.mufrodat.audio', $mufrodat->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="btn-row">
            <input type="file" name="audio" id="audioFileInput" accept=".mp3,.wav,.ogg" required class="form-input" style="flex:1" onchange="previewAudioFile(this)">
            <button type="button" id="previewAudioBtn" class="btn btn-secondary" style="display:none" onclick="document.getElementById('audioPreviewPlayer').play()">▶️ Coba dengarkan</button>
            <button type="submit" class="btn btn-primary">{{ $mufrodat->audio ? 'Ganti audio' : 'Upload audio' }}</button>
        </div>
        <audio id="audioPreviewPlayer" style="display:none"></audio>
        @error('audio')<div class="form-error">{{ $message }}</div>@enderror
        <div style="font-size:11px;color:var(--gray-400);margin-top:6px">Format: mp3, wav, atau ogg. Maksimal 5MB.</div>
    </form>
</div>
@endif

<script>
    let audioPreviewUrl = null;
    function previewAudioFile(input) {
        const btn = document.getElementById('previewAudioBtn');
        const player = document.getElementById('audioPreviewPlayer');
        if (audioPreviewUrl) {
            URL.revokeObjectURL(audioPreviewUrl);
            audioPreviewUrl = null;
        }
        if (input.files && input.files[0]) {
            audioPreviewUrl = URL.createObjectURL(input.files[0]);
            player.src = audioPreviewUrl;
            btn.style.display = 'inline-flex';
        } else {
            btn.style.display = 'none';
        }
    }
</script>
</div>
@endsection
