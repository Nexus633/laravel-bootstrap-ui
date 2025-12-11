@props([
    'footer' => false, // Wenn true, wird <tfoot> gerendert
    'variant' => null, // z.B. 'light' oder 'dark'
])

@php
    $tag = $footer ? 'tfoot' : 'thead';

    // Klasse vorbereiten (oder null, wenn keine Variante)
    $variantClass = $variant ? 'table-' . $variant : null;
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $variantClass]) }}>
    <tr>
        {{ $slot }}
    </tr>
</{{ $tag }}>
