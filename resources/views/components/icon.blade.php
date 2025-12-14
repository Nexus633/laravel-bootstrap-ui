@props([
    'name',
    /*
     * Icon Größe:
     * Zahl (1-6) -> fs-{n} Klasse (Bootstrap Standard)
     * String (z.B. '4rem', '60px') -> style="font-size: ..."
     */
    'size' => null,
    'variant' => null, // primary, secondary, danger, success, etc.
    'asInput' => false,
])
@php
    use Nexus633\BootstrapUi\Facades\Icon;
    $asInput = $attributes->get('as:input') ?? $asInput;

    // 1. Basis-Klasse
    $classes = Icon::toClass($name);

    // Variable für CSS-Style initialisieren
    $customStyle = null;

// 2. Größe hinzufügen
    if ($size) {
        if (is_numeric($size)) {
            // Bootstrap Klasse nutzen
            $classes .= ' fs-' . $size;
        } else {
            // Custom CSS Style vorbereiten
            $customStyle = 'font-size: ' . $size . ';';
        }
    }

    // 3. Variante (Farbe) hinzufügen
    // Aus variant="danger" wird class="text-danger"
    if ($variant) {
        $classes .= ' text-' . $variant;
    }
@endphp


@if($asInput)
    <x-bs::input-group.text>
        <i {{ $attributes->merge(['class' => $classes, 'style' => $customStyle]) }} aria-hidden="true"></i>
    </x-bs::input-group.text>
@else
    <i {{ $attributes->merge(['class' => $classes, 'style' => $customStyle]) }} aria-hidden="true"></i>
@endif
