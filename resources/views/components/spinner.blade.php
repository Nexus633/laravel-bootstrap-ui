@props([
    'type' => 'border', // 'border' (Kreis) oder 'grow' (Pulsieren)
    'variant' => null,  // primary, secondary, light...
    'size' => null,     // 'sm' (klein) oder null (normal)
    'label' => 'Loading...' // Für Screenreader (Barrierefreiheit)
])

@php
    $classes = [
        'spinner-' . $type,
    ];

    if ($variant) {
        $classes[] = 'text-' . $variant;
    }

    if ($size) {
        $classes[] = 'spinner-' . $type . '-' . $size;
    }
@endphp

<div role="status" {{ $attributes->class($classes) }} aria-hidden="true">
    <span class="visually-hidden">{{ $label }}</span>
</div>
