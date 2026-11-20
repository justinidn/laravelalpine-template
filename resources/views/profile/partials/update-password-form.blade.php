<section class="card shadow-sm h-100">
    <div class="card-header bg-body">
        <h2 class="h6 fw-bold mb-1">Ubah Kata Sandi</h2>
        <p class="small text-body-secondary mb-0">Gunakan kata sandi yang kuat dan unik.</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <x-form.password name="current_password" label="Kata Sandi Saat Ini" :class="$errors->getBag('updatePassword')->has('current_password') ? 'is-invalid' : ''" autocomplete="current-password" />
            @error('current_password', 'updatePassword')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <x-form.password name="password" label="Kata Sandi Baru" :class="$errors->getBag('updatePassword')->has('password') ? 'is-invalid' : ''" :toggle="true" autocomplete="new-password" />
            @error('password', 'updatePassword')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <x-form.password-strength name="password_confirmation" label="Konfirmasi Kata Sandi Baru" autocomplete="new-password" />

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-key me-1"></i>Ubah kata sandi
                </button>

                @if (session('status') === 'password-updated')
                <span class="small text-success" role="status">Kata sandi berhasil diubah.</span>
                @endif
            </div>
        </form>
    </div>
</section>