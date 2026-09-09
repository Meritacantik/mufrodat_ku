@extends('layouts.guest-murfodat')

@section('title', 'Login')

@section('content')
<div class="auth-heading" id="heading-text">Masuk sebagai Siswa</div>
<div class="auth-desc" id="desc-text">Masukkan NIS dan kata sandi kamu</div>

<div class="tab-row">
    <button type="button" class="tab-btn active" id="tab-siswa" onclick="pilihTab('siswa')"><x-icon name="backpack" size="14"/> Siswa</button>
    <button type="button" class="tab-btn" id="tab-admin" onclick="pilihTab('admin')"><x-icon name="graduation-cap" size="14"/> Admin / Guru</button>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label class="form-label" id="label-nis" for="nis">Nomor Induk Siswa (NIS)</label>
        <div class="input-wrap">
            <span class="input-icon" id="icon-nis"><x-icon name="id-card" size="14"/></span>
            <input id="nis" class="form-input" type="text" name="nis" value="{{ old('nis') }}" required autofocus autocomplete="username" placeholder="Masukkan NIS kamu">
        </div>
        @error('nis')
        <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div class="input-wrap">
            <span class="input-icon"><x-icon name="lock" size="14"/></span>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            <button type="button" class="toggle-eye" onclick="togglePw('password', this)"><x-icon name="eye" size="14"/></button>
        </div>
        @error('password')
        <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--gray-500);margin-bottom:6px">
        <input type="checkbox" name="remember"> Ingat saya
    </label>

    <button type="submit" class="btn-primary" id="btn-submit">Masuk</button>
</form>

<div class="auth-footer" id="footer-daftar">
    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
</div>

<script>
    const svgEye = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
    const svgEyeOff = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle"><path d="M3 3l18 18"/><path d="M10.6 5.2A9.9 9.9 0 0 1 12 5c6.5 0 10 7 10 7a15.2 15.2 0 0 1-3.4 4.3"/><path d="M6.5 6.6C4 8.2 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 3.9-.8"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>';

    function togglePw(id, btn) {
        const el = document.getElementById(id);
        const isPw = el.type === 'password';
        el.type = isPw ? 'text' : 'password';
        btn.innerHTML = isPw ? svgEyeOff : svgEye;
    }

    function pilihTab(jenis) {
        const isSiswa = jenis === 'siswa';
        const tabSiswa = document.getElementById('tab-siswa');
        const tabAdmin = document.getElementById('tab-admin');
        const btnSubmit = document.getElementById('btn-submit');

        tabSiswa.classList.toggle('active', isSiswa);
        tabAdmin.classList.toggle('active', !isSiswa);
        tabSiswa.style.background = isSiswa ? 'var(--green)' : '';
        tabSiswa.style.color = isSiswa ? 'white' : '';
        tabAdmin.style.background = !isSiswa ? 'var(--red)' : '';
        tabAdmin.style.color = !isSiswa ? 'white' : '';
        btnSubmit.style.background = isSiswa ? 'var(--green)' : 'var(--red)';
        btnSubmit.style.color = 'white';

        const logoIcon = document.getElementById('logo-icon');
        if (logoIcon) {
            logoIcon.style.background = isSiswa ? 'var(--green)' : 'var(--red)';
        }

        document.getElementById('heading-text').textContent = isSiswa ? 'Masuk sebagai Siswa' : 'Masuk sebagai Admin / Guru';
        document.getElementById('desc-text').textContent = isSiswa ? 'Masukkan NIS dan kata sandi kamu' : 'Masukkan username dan kata sandi admin';
        document.getElementById('label-nis').textContent = isSiswa ? 'Nomor Induk Siswa (NIS)' : 'Username';
        document.getElementById('icon-nis').innerHTML = isSiswa
            ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="12" r="2"/><path d="M7 16.5c0-1.4 1-2.5 2-2.5s2 1.1 2 2.5"/><path d="M14 10h4"/><path d="M14 14h4"/></svg>'
            : '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>';
        document.getElementById('nis').placeholder = isSiswa ? 'Masukkan NIS kamu' : 'Masukkan username admin';
        document.getElementById('footer-daftar').style.display = isSiswa ? 'block' : 'none';
    }

    pilihTab('siswa');
</script>
@endsection
