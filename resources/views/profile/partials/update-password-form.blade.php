<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="form-group">
        <label class="form-label" for="update_password_current_password">Password Saat Ini</label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-input" autocomplete="current-password">
        @error('current_password', 'updatePassword')
        <div class="form-error" style="font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="update_password_password">Password Baru</label>
        <input id="update_password_password" name="password" type="password" class="form-input" autocomplete="new-password">
        @error('password', 'updatePassword')
        <div class="form-error" style="font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="update_password_password_confirmation">Konfirmasi Password Baru</label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password">
        @error('password_confirmation', 'updatePassword')
        <div class="form-error" style="font-size:11px;color:var(--red);margin-top:4px">{{ $message }}</div>
        @enderror
    </div>

    <div class="btn-row" style="align-items:center">
        <button type="submit" class="btn btn-primary">Simpan</button>
        @if (session('status') === 'password-updated')
        <span style="font-size:12px;color:var(--green-dark)">Tersimpan.</span>
        @endif
    </div>
</form>
