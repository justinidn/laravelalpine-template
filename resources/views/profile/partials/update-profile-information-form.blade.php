<section class="card shadow-sm h-100">
    <div class="card-header bg-body">
        <h2 class="h6 fw-bold mb-1">Informasi Profil</h2>
        <p class="small text-body-secondary mb-0">Perbarui nama dan alamat email akun Anda.</p>
    </div>

    <div class="card-body">
        <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $user->email }}">
        </form>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <x-form.input name="name" label="Nama Lengkap" :value="old('name', $user->name)" :class="$errors->getBag('updateProfileInformation')->has('name') ? 'is-invalid' : ''" required autocomplete="name" />
            @error('name', 'updateProfileInformation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <x-form.input name="email" label="Alamat Email" type="email" :value="old('email', $user->email)" :class="$errors->getBag('updateProfileInformation')->has('email') ? 'is-invalid' : ''" required autocomplete="email" inputmode="email" />
            @error('email', 'updateProfileInformation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @if (! $user->hasVerifiedEmail())
            <div class="alert alert-warning small" role="status">
                Email Anda belum dikonfirmasi.
                <button type="submit" form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                    Kirim ulang tautan konfirmasi
                </button>
            </div>
            @endif

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-floppy me-1"></i>Simpan profil
                </button>

                @if (session('status') === 'profile-updated')
                <span class="small text-success" role="status">Profil berhasil disimpan.</span>
                @endif
            </div>
        </form>
    </div>
</section>