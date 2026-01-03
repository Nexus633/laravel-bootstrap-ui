@props([
    'name' => null,
    'label' => null,
    'options' => [],
    'placeholder' => null,
    'hint' => null,
    'disabled' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make($name);

    $id = $attributes->getOrCreateId('select-');
    $hasError = $field->hasError();

    $field->addClass('form-select')
          ->addClassWhen($hasError, 'is-invalid');
@endphp

<div class="mb-3">
    @if($label)
    <label for="{{ $id }}" class="form-label">
        {{ $label }}
    </label>
    @endif

    <select
        id="{{ $id }}"
        @if($name) name="{{ $name }}" @endif
        @if($disabled) disabled @endif
        {{ $attributes->class($field->getClasses()) }}
    >
        @if($placeholder)
            <option value="" selected>{{ $placeholder }}</option>
        @endif

        @foreach($options as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach

        {{ $slot }}
    </select>

    @if($hasError)
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @elseif($hint)
        <div class="form-text text-muted">
            {{ $hint }}
        </div>
    @endif
</div>