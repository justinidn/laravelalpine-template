@props(['name' => 'password', 'label' => 'Kata Sandi', 'forgotRoute' => null, 'toggle' => false])

<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center mb-1 gap-2">
        <label for="{{ $name }}" class="form-label small fw-semibold text-body-secondary mb-0">{{ $label }}</label>
        @if ($forgotRoute && Route::has($forgotRoute))
        <a href="{{ route($forgotRoute) }}" class="small text-decoration-none fw-semibold text-nowrap">Lupa?<span class="d-none sm:d-inline"> kata sandi</span></a>
        @endif
    </div>

    <div class="input-group input-group-lg">
        <span class="input-group-text bg-body-tertiary" id="{{ $name }}-addon"><i class="bi bi-lock text-body-secondary"></i></span>
        <input type="password" id="{{ $name }}" name="{{ $name }}" placeholder="••••••••" aria-label="{{ $label }}" aria-describedby="{{ $name }}-addon" {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
        @if ($toggle)
        <button class="btn btn-outline-secondary bg-body-tertiary" type="button" onclick="(function(b){var i=b.parentNode.querySelector('input');var t=i.type==='password';i.type=t?'text':'password';b.querySelector('i').className=t?'bi bi-eye-slash':'bi bi-eye';})(this)" aria-label="Tampilkan atau sembunyikan kata sandi"><i class="bi bi-eye"></i></button>
        @endif
    </div>

    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>