@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => '',
    'icon' => null,
    'loading' => false,
    'disabled' => false,
    'href' => null,
    'target' => '_self'
])

@php
    $classes = 'btn btn-' . $variant;
    if ($size) {
        $classes .= ' btn-' . $size;
    }
    if ($loading) {
        $classes .= ' disabled';
    }

    $tag = $href ? 'a' : 'button';
@endphp

@if($href)
    <a href="{{ $href }}" target="{{ $target }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($loading)
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        @elseif($icon)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}
            @if($disabled || $loading) disabled @endif>
        @if($loading)
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        @elseif($icon)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $slot }}
    </button>
@endif