@extends('layouts.app-murfodat')

@section('title', 'Profil Saya')

@section('styles')
@vite(['resources/css/app.css'])
@endsection

@section('content')
<div class="card" style="margin-bottom:16px">
    <div class="btn-row" style="justify-content:space-between;align-items:center">
        <div class="btn-row" style="align-items:center;gap:14px">
            <div style="width:56px;height:56px;border-radius:50%;background:var(--green);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;flex-shrink:0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <div class="page-title" style="margin-bottom:2px">{{ $user->name }}</div>
                <div class="page-sub" style="margin-bottom:0">Level {{ $user->kelas ?? '-' }} · {{ $kuisSelesai }} kuis selesai</div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('modal-profil').style.display='flex'" style="display:inline-flex;align-items:center;gap:6px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
            </svg>
            Ubah profil
        </button>
    </div>
</div>

<div class="stat-row">
    <div class="stat-c" style="background:var(--amber-light);border:1px solid #f0dfb8">
        <div class="stat-top">
            <div class="stat-icon" style="color:var(--amber)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3.5 14.6 9l6 .87-4.3 4.2 1 6-5.3-2.8-5.3 2.8 1-6L3.4 9.9l6-.87Z"/>
                </svg>
            </div>
        </div>
        <div class="stat-val" style="color:var(--amber)">{{ number_format($totalPoin) }}</div>
        <div class="stat-label" style="color:var(--amber)">Total poin</div>
    </div>
    <div class="stat-c" style="background:var(--green-light);border:1px solid #cfe6d4">
        <div class="stat-top">
            <div class="stat-icon" style="color:var(--green-dark)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="4" width="14" height="17" rx="2"/>
                    <path d="M9 3.5h6a1 1 0 0 1 1 1V6H8V4.5a1 1 0 0 1 1-1Z"/>
                    <path d="m9 13 2 2 4-4"/>
                </svg>
            </div>
        </div>
        <div class="stat-val" style="color:var(--green-dark)">{{ $kuisSelesai }}</div>
        <div class="stat-label" style="color:var(--green-dark)">Kuis selesai</div>
    </div>
    <div class="stat-c stat-white">
        <div class="stat-top">
            <div class="stat-icon" style="color:var(--gray-700)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 5.5a2 2 0 0 1 2-2h5.5v17H6a2 2 0 0 1-2-2Z"/>
                    <path d="M20 5.5a2 2 0 0 0-2-2h-5.5v17H18a2 2 0 0 0 2-2Z"/>
                </svg>
            </div>
        </div>
        <div class="stat-val" style="color:var(--gray-900)">{{ $kataDikuasai }}</div>
        <div class="stat-label">Kata dikuasai</div>
    </div>
</div>

<div class="card" style="margin-bottom:16px">
    <div class="card-title" style="display:flex;align-items:center;gap:8px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 20V10"/><path d="M12 20V4"/><path d="M20 20v-7"/>
        </svg>
        Level materi (per kategori kelas)
    </div>
    <div class="page-sub" style="font-size:11px;margin-bottom:14px">
        Level di sistem ini mengikuti tingkatan materi berdasarkan kelas, bukan level permainan generik.
    </div>
    @foreach(['VII' => 'fill-green', 'VIII' => 'fill-red', 'IX' => 'fill-amber'] as $kelas => $fill)
    <div class="progress-wrap" style="{{ $loop->last ? 'margin-bottom:0' : '' }}">
        <div class="progress-meta">
            <span>Level {{ $kelas }}</span>
            <span style="font-weight:600">{{ $levelKelas[$kelas]['dikuasai'] }} / {{ $levelKelas[$kelas]['total'] }} kata{{ $kelas === ($user->kelas ?? '') ? ' . sedang berjalan' : '' }}</span>
        </div>
        <div class="progress-bar"><div class="progress-fill {{ $fill }}" style="width:{{ $levelKelas[$kelas]['persen'] }}%"></div></div>
    </div>
    @endforeach
</div>

<div class="btn-row" style="margin-bottom:20px">
    <button type="button" class="btn btn-secondary" onclick="document.getElementById('modal-password').style.display='flex'" style="display:inline-flex;align-items:center;gap:6px">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="4" y="11" width="16" height="9" rx="2"/>
            <path d="M8 11V7a4 4 0 0 1 8 0"/>
        </svg>
        Ubah password
    </button>
</div>

<div class="card" style="border-color:#F5D9D5">
    <div class="card-title" style="color:var(--red);display:flex;align-items:center;gap:8px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 9v4"/><path d="M12 17h.01"/>
            <path d="M10.3 3.9 2 18a1.5 1.5 0 0 0 1.3 2.2h17.4A1.5 1.5 0 0 0 22 18L13.7 3.9a1.5 1.5 0 0 0-2.6 0Z"/>
        </svg>
        Hapus akun
    </div>
    @include('profile.partials.delete-user-form')
</div>

{{-- Modal: Ubah Profil --}}
<div id="modal-profil" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:100;align-items:center;justify-content:center;padding:20px" onclick="if(event.target===this) this.style.display='none'">
    <div class="card" style="max-width:420px;width:100%;margin:0">
        <div class="btn-row" style="justify-content:space-between;align-items:center;margin-bottom:12px">
            <div class="card-title" style="margin-bottom:0;display:flex;align-items:center;gap:8px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
                Ubah profil
            </div>
            <button type="button" onclick="document.getElementById('modal-profil').style.display='none'" style="background:none;border:none;cursor:pointer;color:var(--gray-400)"><x-icon name="x" size="16"/></button>
        </div>
        @include('profile.partials.update-profile-information-form')
    </div>
</div>

{{-- Modal: Ubah Password --}}
<div id="modal-password" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:100;align-items:center;justify-content:center;padding:20px" onclick="if(event.target===this) this.style.display='none'">
    <div class="card" style="max-width:420px;width:100%;margin:0">
        <div class="btn-row" style="justify-content:space-between;align-items:center;margin-bottom:12px">
            <div class="card-title" style="margin-bottom:0;display:flex;align-items:center;gap:8px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="11" width="16" height="9" rx="2"/>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                </svg>
                Ubah password
            </div>
            <button type="button" onclick="document.getElementById('modal-password').style.display='none'" style="background:none;border:none;cursor:pointer;color:var(--gray-400)"><x-icon name="x" size="16"/></button>
        </div>
        @include('profile.partials.update-password-form')
    </div>
</div>

<script>
    @if($errors->updateProfileInformation->any())
        document.getElementById('modal-profil').style.display = 'flex';
    @endif
    @if($errors->updatePassword->any())
        document.getElementById('modal-password').style.display = 'flex';
    @endif
</script>
@endsection
