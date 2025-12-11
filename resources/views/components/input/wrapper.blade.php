@props([
    'label' => null,
    'id' => null,      
    'name' => null,    // Wichtig für Error-Check
    'hint' => null,
    'floating' => false, // Wenn true, darf der Wrapper das Label NICHT rendern
])

@php
    $hasError = $name && $errors->has($name);
@endphp

<div class="mb-3">
    {{-- 
       STANDARD LABEL:
       Nur anzeigen, wenn wir NICHT im Floating-Modus sind.
       Bei Floating Labels muss das Label nämlich zwingend NACH dem Input stehen.
    --}}
    @if($label && !$floating)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    {{-- Hier kommt der eigentliche Input oder die Group rein --}}
    {{ $slot }}

    {{-- ERROR & HINT --}}
    @if($hasError)
        <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
    @elseif($hint)
        <div class="form-text text-muted">{{ $hint }}</div>
    @endif
</div>
