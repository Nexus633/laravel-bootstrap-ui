@props([
    'name',
    'label' => null,
    'type' => 'checkbox',  // 'checkbox' oder 'radio'
    'value' => 1,
    'checked' => false,
    'inline' => false,
    'switch' => false,     // Kürzer als isSwitch
    'hint' => null,
    'id' => null,
])

@php
    $id = $id ?? 'check-' . uniqid();
    $hasError = $name && $errors->has($name);

    // 1. Wrapper Klassen (Array Logik)
    $wrapperClasses = [
        'form-check',
        $switch ? 'form-switch' : null,
        $inline ? 'form-check-inline' : null,
        !$inline ? 'mb-3' : null, // Abstand nur, wenn nicht inline
        // Wenn Inline, setzen wir min-height, damit es sauber ausrichtet
        $inline ? 'd-inline-flex align-items-center' : null
    ];

    // 2. Input Klassen
    $inputClasses = [
        'form-check-input',
        $hasError ? 'is-invalid' : null,
    ];

    // 3. Arrays zu Strings wandeln
    $wrapperClassString = implode(' ', array_filter($wrapperClasses));
    $inputClassString   = implode(' ', array_filter($inputClasses));
@endphp

<div class="{{ $wrapperClassString }}">
    <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ $value }}"

            {{-- Merge Input Klassen --}}
            {{ $attributes->class($inputClassString) }}

            {{-- Switch Rolle für Accessibility --}}
            @if($switch) role="switch" @endif

            {{-- Standard HTML Checked state (Livewire ignoriert das, wenn wire:model da ist) --}}
            @if($checked) checked @endif
    />

    @if($label)
        <label class="form-check-label" for="{{ $id }}">
            {{ $label }}
        </label>
    @endif

    {{-- Error & Hint MÜSSEN innerhalb von form-check stehen --}}
    @if($hasError)
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @elseif($hint)
        <div class="form-text">{{ $hint }}</div>
    @endif
</div>
