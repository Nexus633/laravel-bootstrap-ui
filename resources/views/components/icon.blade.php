@props([
    'name',
    'size' => null,    // 1 bis 6 (für fs-1, fs-2...)
    'variant' => null, // primary, secondary, danger, success, etc.
    'asInput' => false,
])
@php
    $asInput = $attributes->get('as:input') ?? $asInput;

    // 1. Intelligent das Präfix handhaben
    $iconName = str_starts_with($name, 'bi-') ? $name : 'bi-' . $name;

    // 2. Basis-Klasse
    $classes = 'bi ' . $iconName;

    // 3. Größe hinzufügen
    if ($size) {
        $classes .= ' fs-' . $size;
    }

    // 4. Variante (Farbe) hinzufügen
    // Aus variant="danger" wird class="text-danger"
    if ($variant) {
        $classes .= ' text-' . $variant;
    }
@endphp


@if($asInput)
    <span class="input-group-text">
        <i {{ $attributes->merge(['class' => $classes]) }} aria-hidden="true"></i>
    </span>
@else
    <i {{ $attributes->merge(['class' => $classes]) }} aria-hidden="true"></i>
@endif
