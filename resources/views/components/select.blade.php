@props([
'name',
'label' => null,
'options' => [],       // Array: ['value' => 'Label']
'placeholder' => null, // Z.B. "Bitte wählen..."
'hint' => null,
'disabled' => false,
])

@php
// ID generieren (für Label-Verknüpfung)
$wireModel = $attributes->wire('model');
$id = $attributes->get('id') ?? 'select-' . uniqid();

// Fehler prüfen
$hasError = $name && $errors->has($name);
@endphp

<div class="mb-3">
    {{-- Label --}}
    @if($label)
    <label for="{{ $id }}" class="form-label">
        {{ $label }}
    </label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if($disabled) disabled @endif
        {{ $attributes->class(['form-select', 'is-invalid' => $hasError]) }}
        >
        {{-- 1. Placeholder Option (leer & disabled) --}}
        @if($placeholder)
        <option value="" selected>{{ $placeholder }}</option>
        @endif

        {{-- 2. Automatische Optionen aus Array --}}
        @foreach($options as $value => $text)
        <option value="{{ $value }}">{{ $text }}</option>
        @endforeach

        {{-- 3. Manueller Slot (falls man optgroups oder spezielle Logik braucht) --}}
        {{ $slot }}
    </select>

    {{-- Feedback: Error oder Hint --}}
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
