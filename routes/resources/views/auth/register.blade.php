@extends('layouts.guest-murfodat')

@section('title', 'Daftar Akun')

@section('header')
<div class="auth-icon-only"><x-icon name="graduation-cap" size="28"/></div>
@endsection

@section('content')
<div class="auth-heading" style="font-size:22px;margin-bottom:6px">Daftar Akun Siswa</div>
<div class="auth-desc" style="font-size:13px;margin-bottom:22px">Isi formulir dibawah untuk bergabung dengan mufrodatku</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <label class="form-label" for="name"><x-icon name="user" size="13"/> Nama Lengkap</label>
        <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Contoh: Merita">
        @error('name')
        <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="nis"><x-icon name="id-card" size="13"/> Nomor Induk Siswa (NIS)</label>
        <input id="nis" class="form-input" type="text" name="nis" value="{{ old('nis') }}" required autocomplete="username" placeholder="Masukkan NIS kamu">
        @error('nis')
        <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password"><x-icon name="lock" size="13"/> Password</label>
        <div class="input-wrap">
            <input id="password" class="form-input" style="padding-left:12px" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 Karakter">
            <button type="button" class="toggle-eye" onclick="togglePw('password', this)"><x-icon name="eye" size="13"/></button>
        </div>
        @error('password')
        <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password_confirmation"><x-icon name="key" size="13"/> Konfirmasi Password</label>
        <div class="input-wrap">
            <input id="password_confirmation" class="form-input" style="padding-left:12px" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password">
            <button type="button" class="toggle-eye" onclick="togglePw('password_confirmation', this)"><x-icon name="eye" size="13"/></button>
        </div>
        @error('password_confirmation')
        <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="kelas"><x-icon name="graduation-cap" size="13"/> Kelas</label>
        <select id="kelas" name="kelas" class="form-select" required>
            <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>-- Pilih kelas --</option>
            @foreach(['VII', 'VIII', 'IX'] as $k)
            <option value="{{ $k }}" {{ old('kelas') === $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
            @endforeach
        </select>
        @error('kelas')
        <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn-primary">Daftar Sekarang</button>
</form>

<div class="auth-divider">Sudah punya akun? <a href="{{ route('login') }}" style="text-decoration:underline;color:var(--green-dark);font-weight:600">Login</a></div>

<script>
    const svgEye = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
    const svgEyeOff = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle"><path d="M3 3l18 18"/><path d="M10.6 5.2A9.9 9.9 0 0 1 12 5c6.5 0 10 7 10 7a15.2 15.2 0 0 1-3.4 4.3"/><path d="M6.5 6.6C4 8.2 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 3.9-.8"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>';

    function togglePw(id, btn) {
        const el = document.getElementById(id);
        const isPw = el.type === 'password';
        el.type = isPw ? 'text' : 'password';
        btn.innerHTML = isPw ? svgEyeOff : svgEye;
    }
</script>
@endsection
