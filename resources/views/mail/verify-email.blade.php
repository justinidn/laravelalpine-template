@component('mail::message')
# Halo, {{ $user->name }}

Terima kasih telah mendaftar di **{{ config('app.name') }}**. Konfirmasi alamat email Anda untuk mengaktifkan akses akun.

@component('mail::button', ['url' => $url])
Konfirmasi Email
@endcomponent

Jika tombol tidak berfungsi, salin dan buka tautan berikut di browser:

{{ $url }}

Jika Anda tidak merasa membuat akun, abaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
