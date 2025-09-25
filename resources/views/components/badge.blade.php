@props([
    'variant' => 'primary',
    'pill' => false,
    'icon' => null
])

@php
    $classes = 'badge bg-' . $variant;
    if ($pill) {
        $classes .= ' rounded-pill';
    }
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="{{ $icon }} me-1"></i>
    @endif
    {{ $slot }}
</span>