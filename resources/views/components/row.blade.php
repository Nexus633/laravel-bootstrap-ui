@props([
    'g' => null,   // Gutter (Abstand) allgemein: 0-5
    'gx' => null,  // Horizontaler Abstand
    'gy' => null,  // Vertikaler Abstand
])

@php
    $classes = 'row';

    // Gutter Logik
    if ($g !== null) {
        $classes .= " g-$g";
    }

    // Spezifische Gutters (überschreiben g oft)
    if ($gx !== null) {
        $classes .= " gx-$gx";
    }
    if ($gy !== null) {
        $classes .= " gy-$gy";
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} {{ $attributes }}>
    {{ $slot }}
</div>
