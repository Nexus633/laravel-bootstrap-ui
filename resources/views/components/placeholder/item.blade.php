@props([
    'cols' => null,      // 1-12
    'width' => null,     // z.B. '75%'
    'size' => null,      // xs, sm, lg
    'variant' => null,   // primary, secondary...
    'button' => false,   // Button-Look
])

@php
    // Tag bestimmen: 'a' für Buttons (damit disabled styling greift), sonst 'span'
    $tag = $button ? 'a' : 'span';
@endphp

<{{ $tag }}
    {{ $attributes->class([
        'placeholder',
        'col-' . $cols => $cols,                  // Nur wenn $cols gesetzt ist
        'placeholder-' . $size => $size,          // Nur wenn $size gesetzt ist
        'bg-' . $variant => $variant,             // Nur wenn $variant gesetzt ist
        'btn disabled' => $button,                // Nur wenn button=true
    ]) }}
    @if($width) style="width: {{ $width }};" @endif
@if($button) href="#" tabindex="-1" @endif
></{{ $tag }}>
