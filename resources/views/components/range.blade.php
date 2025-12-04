@props([
    'name',
    'label' => null,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'hint' => null,
])

@php
    // ID-Generierung, wie bei Inputs
    $id = $attributes->get('id') ?? 'range-' . uniqid();
    $hasError = $name && $errors->has($name);
@endphp

<div class="mb-3">

    {{-- Label über dem Range-Input (Standard-Input-Stil) --}}
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
        </label>
    @endif

    <input
            type="range"
            name="{{ $name }}"
            id="{{ $id }}"
            min="{{ $min }}"
            max="{{ $max }}"
            step="{{ $step }}"
            {{-- Wichtig: Bootstrap Range Klasse --}}
            {{ $attributes->class(['form-range', 'is-invalid' => $hasError]) }}
    />

    {{-- Fehler- und Hinweis-Feedback --}}
    @if($hasError)
        <div class="invalid-feedback d-block">
            {{ $errors->first($name) }}
        </div>
    @elseif($hint)
        <div class="form-text text-muted">
            {{ $hint }}
        </div>
    @endif
</div>
