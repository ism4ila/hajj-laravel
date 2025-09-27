@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => null,
    'prepend' => null,
    'append' => null
])

@php
    $inputId = 'input_' . $name;
    $hasError = isset($errors) ? $errors->has($name) : false;
    $value = old($name, $value);
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $inputId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @if($prepend || $append)
        <div class="input-group @if($hasError) is-invalid @endif">
            @if($prepend)
                <span class="input-group-text">{{ $prepend }}</span>
            @endif

            <input type="{{ $type }}"
                   id="{{ $inputId }}"
                   name="{{ $name }}"
                   value="{{ $value }}"
                   @if($placeholder) placeholder="{{ $placeholder }}" @endif
                   @if($required) required @endif
                   @if($disabled) disabled @endif
                   @if($readonly) readonly @endif
                   {{ $attributes->merge(['class' => 'form-control' . ($hasError ? ' is-invalid' : '')]) }}>

            @if($append)
                <span class="input-group-text">{{ $append }}</span>
            @endif
        </div>
    @else
        <input type="{{ $type }}"
               id="{{ $inputId }}"
               name="{{ $name }}"
               value="{{ $value }}"
               @if($placeholder) placeholder="{{ $placeholder }}" @endif
               @if($required) required @endif
               @if($disabled) disabled @endif
               @if($readonly) readonly @endif
               {{ $attributes->merge(['class' => 'form-control' . ($hasError ? ' is-invalid' : '')]) }}>
    @endif

    @if($hasError && isset($errors))
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>