@props([
    'name',
    'label' => null,
    'type' => 'checkbox',  // checkbox oder radio
    'value' => 1,
    'checked' => false,
    'inline' => false,
    'hint' => null,
    'isSwitch' => false,   // Spezieller Trigger für Switch-Styling
])

@php
    $wireModel = $attributes->wire('model');
    
    // ID-Generierung: Zentralisiert für alle Typen
    $id = $attributes->get('id') ?? ($name ?? 'chk') . '-' . str_replace(' ', '', $value) . '-' . md5($wireModel);
    
    $hasError = $name && $errors->has($name);

    // Wrapper Klassen Logik
    $wrapperClass = 'form-check';
    
    if ($isSwitch) {
        $wrapperClass .= ' form-switch';
    }
    
    if ($inline) {
        $wrapperClass .= ' form-check-inline';
    } else {
        // mb-3 nur wenn NICHT inline (sieht sonst komisch aus)
        // Bei Radios ist mb-1 oft besser, aber wir standardisieren hier auf mb-3 für Konsistenz
        $wrapperClass .= ' mb-3'; 
    }
@endphp

<div class="{{ $wrapperClass }}">
    <input
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ $value }}"
            id="{{ $id }}"
            @if($isSwitch) role="switch" @endif
            @if($checked && !$wireModel->value()) checked @endif
            {{-- Wir mergen die Attribute hier, damit wire:model etc. ankommen --}}
            {{ $attributes->class(['form-check-input', 'is-invalid' => $hasError]) }}
    >

    @if($label)
        <label class="form-check-label" for="{{ $id }}">
            {{ $label }}
        </label>
    @endif

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
