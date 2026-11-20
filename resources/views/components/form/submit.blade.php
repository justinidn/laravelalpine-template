@props([
'label' => 'Simpan',
'icon' => null,
'theme' => 'primary',
])

<button type="submit" {{ $attributes->merge(['class' => "btn btn-solid theme-{$theme} btn-sm w-100 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2"]) }}>
    @if ($icon)
    <i class="bi {{ $icon }}"></i>
    @endif
    <span>{{ $label }}</span>
</button>