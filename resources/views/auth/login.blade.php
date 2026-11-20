<x-guest-layout>
    <div class="card border shadow-sm rounded-4 overflow-hidden">
        {{-- Aksen garis atas card — warna primary murni --}}
        <div class="bg-primary" style="height: 4px;"></div>

        <div class="card-body p-4 p-sm-5">
            {{-- Ikon + Judul --}}
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 56px; height: 56px;">
                    <i class="bi bi-shield-lock-fill text-primary fs-4"></i>
                </div>
                <h1 class="h5 fw-bold text-body-emphasis mb-1">Selamat Datang Kembali</h1>
                <p class="small text-body-secondary mb-0">Masuk untuk melanjutkan ke akun Anda</p>
            </div>

            @if (session('status'))
            <div class="alert alert-success small rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <div class="flex-grow-1">{{ session('status') }}</div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                {{-- Kolom Email --}}
                <x-form.input name="email" label="Alamat Email" type="email" icon="bi-envelope" placeholder="Username" required autocomplete="username" inputmode="email" />
                <x-form.password name="password" label="Kata Sandi" forgotRoute="password.request" :toggle="true" required autocomplete="current-password" />
                <div class="form-check mb-4 py-1">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me" @checked(old('remember'))>
                    <label class="form-check-label small text-body-secondary user-select-none" for="remember_me">Ingat saya di perangkat ini</label>
                </div>
                {{-- Tombol Submit — teks putih --}}
                <x-form.submit label="Masuk" icon="bi-box-arrow-in-right" />
                @if (Route::has('register'))
                <p class="text-center small text-body-secondary mt-4 mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Daftar sekarang</a>
                </p>
                @endif

            </form>
        </div>
    </div>
</x-guest-layout>