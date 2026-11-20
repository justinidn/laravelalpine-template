<x-guest-layout title="Konfirmasi Kata Sandi">
    <div class="card border shadow-sm rounded-4 overflow-hidden">
        <div class="bg-primary" style="height: 4px;"></div>

        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <h1 class="h5 fw-bold text-body-emphasis mb-1">Konfirmasi Kata Sandi</h1>
                <p class="small text-body-secondary mb-0">
                    Konfirmasi kata sandi Anda sebelum melanjutkan.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <x-form.password name="password" label="Kata Sandi" required autocomplete="current-password" />
                <x-form.submit label="Konfirmasi" icon="bi-check2-circle" />
            </form>
        </div>
    </div>
</x-guest-layout>
