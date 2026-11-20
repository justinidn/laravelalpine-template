<x-app-layout>
    <x-slot name="header">
        <h1 class="h5 fw-bold text-body-emphasis mb-0">Profil</h1>
    </x-slot>

    <div class="container-fluid flex-grow-1 overflow-auto py-3">
        <div class="row g-3">
            <div class="col-12 col-xl-6">
                @include('profile.partials.update-profile-information-form', ['user' => $user])
            </div>
            <div class="col-12 col-xl-6">
                @include('profile.partials.update-password-form')
            </div>
            <div class="col-12">
                @include('profile.partials.two-factor-authentication-form', [
                    'user' => $user,
                    'twoFactorPasswordConfirmed' => $twoFactorPasswordConfirmed,
                ])
            </div>
            <div class="col-12">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
