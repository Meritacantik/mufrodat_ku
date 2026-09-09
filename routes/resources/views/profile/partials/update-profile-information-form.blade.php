<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="form-group">
        <label class="form-label" for="name">Nama Lengkap</label>
        <input id="name" name="name" type="text" class="form-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name', 'updateProfileInformation')
        <div class="form-error" style="font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>
        @enderror
    </div>

    <div class="btn-row" style="align-items:center">
        <button type="submit" class="btn btn-primary">Simpan</button>
        @if (session('status') === 'profile-updated')
        <span style="font-size:12px;color:var(--green-dark)">Tersimpan.</span>
        @endif
    </div>
</form>
