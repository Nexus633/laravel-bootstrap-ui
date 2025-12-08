@props([
    'value' => 0,          // Prozent (0-100)
    'min' => 0,
    'max' => 100,
    'variant' => null,     // bg-primary, bg-success...
    'striped' => false,    // Gestreift
    'animated' => false,   // Bewegt sich (braucht striped)
    'label' => false,      // Zeigt die % Zahl im Balken an
])

@php
    $classes = ['progress-bar'];

    if ($variant)  $classes[] = 'bg-' . $variant;
    if ($striped)  $classes[] = 'progress-bar-striped';
    if ($animated) $classes[] = 'progress-bar-animated';
    
    // Berechnung für width
    $width = ($value - $min) / ($max - $min) * 100;
@endphp

<div
        role="progressbar"
        aria-valuenow="{{ $value }}"
        aria-valuemin="{{ $min }}"
        aria-valuemax="{{ $max }}"
        style="width: {{ $width }}%"
        {{ $attributes->class($classes) }}
>
    @if($label)
        {{ round($width) }}%
    @else
        {{ $slot }}
    @endif
</div>
