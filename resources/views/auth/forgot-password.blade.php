<x-guest-layout title="Lupa Kata Sandi">
    <div class="card border shadow-sm rounded-4 overflow-hidden">
        <div class="bg-primary" style="height: 4px;"></div>

        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 56px; height: 56px;">
                    <i class="bi bi-key-fill text-primary fs-4"></i>
                </div>
                <h1 class="h5 fw-bold text-body-emphasis mb-1">Lupa Kata Sandi?</h1>
                <p class="small text-body-secondary mb-0">
                    Masukkan alamat email akun Anda. Kami akan mengirim tautan untuk mengatur ulang kata sandi.
                </p>
            </div>

            @if (session('status'))
                <div class="alert alert-success small rounded-3 mb-4" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <x-form.input name="email" label="Alamat Email" type="email" icon="bi-envelope" placeholder="nama@contoh.com" :value="old('email')" required autocomplete="email" inputmode="email" autofocus />
                <x-form.submit label="Kirim tautan reset" icon="bi-send" />

                <p class="text-center small text-body-secondary mt-4 mb-0">
                    Ingat kata sandi?
                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Masuk</a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
