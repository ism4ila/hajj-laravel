@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => null,
    'rows' => 3
])

@php
    $textareaId = 'textarea_' . $name;
    $hasError = isset($errors) ? $errors->has($name) : false;
    $value = old($name, $value);
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $textareaId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <textarea id="{{ $textareaId }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              @if($placeholder) placeholder="{{ $placeholder }}" @endif
              @if($required) required @endif
              @if($disabled) disabled @endif
              @if($readonly) readonly @endif
              {{ $attributes->merge(['class' => 'form-control' . ($hasError ? ' is-invalid' : '')]) }}>{{ $value }}</textarea>

    @if($hasError && isset($errors))
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>