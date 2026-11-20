<section class="card border-danger-subtle shadow-sm">
    <div class="card-header text-danger-emphasis bg-danger-subtle">
        <h2 class="h6 fw-bold mb-0">Hapus Akun</h2>
    </div>

    <div class="card-body">
        <p class="small text-body-secondary">
            Tindakan ini menghapus akun Anda secara permanen dan tidak dapat dibatalkan.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <x-form.input name="password" label="Konfirmasi dengan kata sandi" type="password" autocomplete="current-password" :class="$errors->getBag('userDeletion')->has('password') ? 'is-invalid' : ''" />
            @error('password', 'userDeletion')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-1"></i>Hapus akun
            </button>
        </form>
    </div>
</section>
