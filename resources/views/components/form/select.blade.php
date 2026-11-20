@props([
'name',
'label',
'options' => [],
'placeholder' => 'Pilih salah satu',
'selected' => null,
])

@php
$current = $selected ?? old($name);
@endphp

<div class="form-field mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $value => $text)
        <option value="{{ $value }}" @selected((string) $current===(string) $value)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>