<section class="card shadow-sm">
    <div class="card-header bg-body">
        <h2 class="h6 fw-bold mb-1">Google Authenticator</h2>
        <p class="small text-body-secondary mb-0">
            Lindungi akun dengan kode verifikasi dari Google Authenticator atau aplikasi TOTP lain.
        </p>
    </div>

    <div class="card-body">
        @if (! $twoFactorPasswordConfirmed)
            <div class="alert alert-info mb-0">
                Konfirmasi kata sandi Anda terlebih dahulu untuk mengelola autentikasi dua faktor.
                <a class="alert-link d-inline-block ms-1" href="{{ route('profile.two-factor.confirmed') }}">
                    Konfirmasi kata sandi
                </a>
            </div>
        @elseif ($user->hasEnabledTwoFactorAuthentication())
            <div class="alert alert-success" role="status">
                <i class="bi bi-shield-check me-1"></i>Google Authenticator sudah aktif di akun Anda.
            </div>

            <div class="mb-3" x-data="{ codes: [], error: '' }">
                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm"
                    @click="fetch('{{ route('two-factor.recovery-codes') }}', { headers: { Accept: 'application/json' } })
                        .then(response => {
                            if (! response.ok) throw new Error();
                            return response.json();
                        })
                        .then(data => { codes = data; error = ''; })
                        .catch(() => error = 'Kode pemulihan gagal dimuat. Konfirmasi kata sandi lalu coba lagi.')"
                >
                    <i class="bi bi-eye me-1"></i>Lihat kode pemulihan
                </button>

                <p class="small text-danger mt-2 mb-0" x-show="error" x-text="error" role="alert"></p>

                <ul class="list-group list-group-flush mt-2 font-monospace" x-show="codes.length">
                    <template x-for="code in codes" :key="code">
                        <li class="list-group-item px-0 py-1" x-text="code"></li>
                    </template>
                </ul>

                <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        Buat kode pemulihan baru
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('two-factor.disable') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-shield-x me-1"></i>Nonaktifkan Google Authenticator
                </button>
            </form>
        @elseif ($user->two_factor_secret)
            <div
                class="row g-4 align-items-center"
                x-data="{ svg: '', secretKey: '', error: '' }"
                x-init="Promise.all([
                    fetch('{{ route('two-factor.qr-code') }}', { headers: { Accept: 'application/json' } }).then(response => {
                        if (! response.ok) throw new Error();
                        return response.json();
                    }),
                    fetch('{{ route('two-factor.secret-key') }}', { headers: { Accept: 'application/json' } }).then(response => {
                        if (! response.ok) throw new Error();
                        return response.json();
                    })
                ]).then(([qrCode, key]) => {
                    svg = qrCode.svg;
                    secretKey = key.secretKey;
                }).catch(() => error = 'Kode QR gagal dimuat. Konfirmasi kata sandi lalu muat ulang halaman.')"
            >
                <div class="col-12 col-md-auto text-center">
                    <div class="border rounded-3 bg-white p-3 d-inline-block" x-show="svg" x-html="svg" aria-label="Kode QR Google Authenticator"></div>
                    <p class="small text-body-secondary mt-2 mb-0" x-show="! svg && ! error">Memuat kode QR...</p>
                </div>

                <div class="col">
                    <h3 class="h6 fw-semibold">Hubungkan aplikasi autentikator</h3>
                    <ol class="small text-body-secondary ps-3">
                        <li>Buka Google Authenticator di ponsel Anda.</li>
                        <li>Pindai kode QR atau masukkan kunci rahasia secara manual.</li>
                        <li>Masukkan kode 6 digit dari aplikasi untuk menyelesaikan pengaturan.</li>
                    </ol>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" for="authenticator-secret">Kunci rahasia</label>
                        <code id="authenticator-secret" class="form-control user-select-all" x-text="secretKey || 'Memuat kunci...'"></code>
                    </div>

                    <p class="small text-danger" x-show="error" x-text="error" role="alert"></p>
                </div>
            </div>

            <form method="POST" action="{{ route('two-factor.confirm') }}" class="mt-4">
                @csrf
                <label for="two_factor_code" class="form-label small fw-semibold">Kode verifikasi 6 digit</label>
                <div class="input-group">
                    <input
                        id="two_factor_code"
                        name="code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        autocomplete="one-time-code"
                        class="form-control @error('code') is-invalid @enderror"
                        required
                    >
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i>Verifikasi dan aktifkan
                    </button>
                </div>
                @error('code')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </form>
        @else
            <p class="small text-body-secondary">
                Aktifkan autentikasi dua faktor untuk meminta kode dari aplikasi Google Authenticator setiap kali masuk.
            </p>

            <form method="POST" action="{{ route('two-factor.enable') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-shield-lock me-1"></i>Aktifkan Google Authenticator
                </button>
            </form>
        @endif

        @if (session('status') === Laravel\Fortify\Fortify::TWO_FACTOR_AUTHENTICATION_CONFIRMED)
            <div class="alert alert-success mt-3 mb-0" role="status">
                Google Authenticator berhasil dikonfirmasi dan diaktifkan.
            </div>
        @elseif (session('status') === Laravel\Fortify\Fortify::TWO_FACTOR_AUTHENTICATION_DISABLED)
            <div class="alert alert-info mt-3 mb-0" role="status">
                Autentikasi dua faktor telah dinonaktifkan.
            </div>
        @endif
    </div>
</section>
