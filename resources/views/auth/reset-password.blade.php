<x-guest-layout title="Atur Ulang Kata Sandi">
    <div class="card border shadow-sm rounded-4 overflow-hidden">
        <div class="bg-primary" style="height: 4px;"></div>

        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 56px; height: 56px;">
                    <i class="bi bi-shield-lock-fill text-primary fs-4"></i>
                </div>
                <h1 class="h5 fw-bold text-body-emphasis mb-1">Atur Ulang Kata Sandi</h1>
                <p class="small text-body-secondary mb-0">Buat kata sandi baru untuk akun Anda.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <x-form.input name="email" label="Alamat Email" type="email" icon="bi-envelope" placeholder="nama@contoh.com" :value="old('email', $request->email)" required autocomplete="email" inputmode="email" autofocus />
                <x-form.password name="password" label="Kata Sandi Baru" :toggle="true" required autocomplete="new-password" />
                <x-form.password name="password_confirmation" label="Konfirmasi Kata Sandi Baru" required autocomplete="new-password" />
                <x-form.submit label="Simpan kata sandi baru" icon="bi-check2-circle" />

                <p class="text-center small text-body-secondary mt-4 mb-0">
                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Kembali ke halaman masuk</a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
