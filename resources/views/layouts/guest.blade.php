<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __($title) }} · {{ config('app.name', 'Laravel') }}</title>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Sesuaikan path Vite jika menggunakan SCSS (biasanya resources/sass/app.scss atau resources/css/app.css) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-body-tertiary d-flex align-items-center min-vh-100 py-5">
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4 mx-auto" style="max-width: 420px;">



                {{-- Authentication card --}}
                {{ $slot }}

            </div>
        </div>
    </main>
</body>

</html>