@props([
    'name',                  // Wichtig für wire:model und Error-Bag
    'label' => null,         // Das Label über dem Input
    'type' => 'text',        // text, password, email, number...
    'hint' => null,          // Kleiner Hilfetext unter dem Input
    'prepend' => null,       // Text/Icon VOR dem Input (z.B. '€' oder Icon)
    'append' => null,        // Text/Icon NACH dem Input
])

@php
    // Wir bauen eine ID, falls keine übergeben wurde, damit das Label funktioniert.
    // Wir nutzen 'name' oder generieren einen Hash, falls name fehlt.
    $id = $attributes->get('id') ?? $name ?? 'input-' . md5($attributes->wire('model'));

    // Fehler-Ermittlung: Check, ob es einen Error für diesen Namen gibt
    // Wir nutzen $errors->has(), was in Blade global verfügbar ist.
    $hasError = $name && $errors->has($name);
    
    // Haben wir Prepend/Append Slots oder Props? Dann brauchen wir eine input-group
    $hasGroup = $prepend || $append || $slot->isNotEmpty();
@endphp

<div class="mb-3">
    {{-- 1. Das Label --}}
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
        </label>
    @endif

    {{-- 2. Der Input-Wrapper (Input Group falls nötig) --}}
    @if($hasGroup)
        <div class="input-group {{ $hasError ? 'is-invalid' : '' }}">
            {{-- Prepend (z.B. € Zeichen) --}}
            @if($prepend)
                <span class="input-group-text">{!! $prepend !!}</span>
            @endif

            {{-- Der eigentliche Input (im Group Kontext) --}}
            <input
                    id="{{ $id }}"
                    type="{{ $type }}"
                    name="{{ $name }}"
                    {{ $attributes->class(['form-control', 'is-invalid' => $hasError]) }}
            />

            {{-- Append (z.B. .00) --}}
            @if($append)
                <span class="input-group-text">{!! $append !!}</span>
            @endif
        </div>
    @else
        {{-- Standard Input ohne Group --}}
        <input
                id="{{ $id }}"
                type="{{ $type }}"
                name="{{ $name }}"
                {{ $attributes->class(['form-control', 'is-invalid' => $hasError]) }}
        />
    @endif

    {{-- 3. Feedback: Error oder Hint --}}
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
