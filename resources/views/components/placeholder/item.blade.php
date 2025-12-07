@props([
    'cols' => null,      // Breite als Grid-Spalten (1-12)
    'width' => null,     // Breite manuell (z.B. '75%')
    'size' => null,      // xs, sm, lg
    'variant' => null,   // primary, secondary, light, dark...
    'button' => false,   // Sieht aus wie ein Button
])

@php
    $classes = ['placeholder'];

    // Größenlogik
    if ($cols) {
        $classes[] = 'col-' . $cols;
    }

    // Style Variationen
    if ($size) $classes[] = 'placeholder-' . $size;
    if ($variant) $classes[] = 'bg-' . $variant;

    // Button-Modus
    if ($button) {
        $classes[] = 'btn disabled';
    }
@endphp

{{--
    Wenn 'button' aktiv ist, nutzen wir 'a', sonst 'span'.
    Das ist Bootstrap Standard für Placeholder-Buttons.
--}}
<{{ $button ? 'a' : 'span' }}
    {{ $attributes->class($classes) }}
    @if($width) style="width: {{ $width }};" @endif
@if($button) href="#" tabindex="-1" @endif
></{{ $button ? 'a' : 'span' }}>
