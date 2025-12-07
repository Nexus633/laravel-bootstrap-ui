@props([
    'animation' => 'glow', // 'glow' (pulsieren) oder 'wave' (welle)
    'tag' => 'div',        // div, p, span...
])

@php
    $classes = [
        'placeholder-' . $animation
    ];
@endphp

<{{ $tag }} aria-hidden="true" {{ $attributes->class($classes) }}>
{{ $slot }}
</{{ $tag }}>
