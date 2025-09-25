@props([
    'name',
    'label' => null,
    'value' => null,
    'options' => [],
    'placeholder' => '-- Sélectionner --',
    'required' => false,
    'disabled' => false,
    'help' => null,
    'multiple' => false
])

@php
    $inputId = 'select_' . $name;
    $hasError = $errors->has($name);
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

    <select id="{{ $inputId }}"
            name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif
            {{ $attributes->merge(['class' => 'form-select' . ($hasError ? ' is-invalid' : '')]) }}>

        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            @if(is_array($optionLabel))
                <optgroup label="{{ $optionValue }}">
                    @foreach($optionLabel as $groupValue => $groupLabel)
                        <option value="{{ $groupValue }}"
                                @if(is_array($value) ? in_array($groupValue, $value) : $groupValue == $value) selected @endif>
                            {{ $groupLabel }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $optionValue }}"
                        @if(is_array($value) ? in_array($optionValue, $value) : $optionValue == $value) selected @endif>
                    {{ $optionLabel }}
                </option>
            @endif
        @endforeach
    </select>

    @if($hasError)
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>