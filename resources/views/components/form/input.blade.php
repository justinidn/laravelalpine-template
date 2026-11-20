@props(['name', 'label', 'type' => 'text', 'icon' => null, 'addon' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label small fw-semibold text-body-secondary">{{ $label }}</label>

    @if ($icon || $addon)
    <div class="input-group input-group-lg">
        @if ($icon)
        <span class="input-group-text bg-body-tertiary" id="{{ $name }}-addon"><i class="bi {{ $icon }} text-body-secondary"></i></span>
        @elseif ($addon)
        <span class="input-group-text" id="{{ $name }}-addon">{{ $addon }}</span>
        @endif
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" aria-describedby="{{ $name }}-addon" {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    </div>
    @else
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control form-control-lg' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    @endif

    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>