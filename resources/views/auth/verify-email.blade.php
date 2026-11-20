<x-guest-layout title="Verifikasi Email">
    <div class="card border shadow-sm rounded-4 overflow-hidden">
        <div class="bg-primary" style="height: 4px;"></div>

        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 56px; height: 56px;">
                    <i class="bi bi-envelope-check-fill text-primary fs-4"></i>
                </div>
                <h1 class="h5 fw-bold text-body-emphasis mb-1">Konfirmasi Email Anda</h1>
                <p class="small text-body-secondary mb-0">
                    Kami mengirim tautan konfirmasi. Konfirmasi email sebelum masuk ke akun.
                </p>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success small rounded-3 mb-4" role="status">
                    Jika email terdaftar dan belum dikonfirmasi, tautan konfirmasi akan dikirim.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-form.input name="email" label="Alamat Email" type="email" icon="bi-envelope" placeholder="nama@contoh.com" :value="old('email', session('email'))" required autocomplete="email" inputmode="email" />

                <button type="submit" class="btn bg-primary text-white btn-lg w-100 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-send text-white"></i>
                    <span class="text-white">Kirim ulang tautan konfirmasi</span>
                </button>

                <p class="text-center small text-body-secondary mt-4 mb-0">
                    Sudah mengonfirmasi email?
                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Masuk</a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
