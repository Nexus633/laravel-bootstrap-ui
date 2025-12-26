@props([
    'bodyVariant' => 'tertiary'
])

@php
    $bodyVariant = $attributes->get('body:variant', $bodyVariant);
    $attributes = $attributes->except(['body:variant']);

    $bodyColor = 'bg-body-' . $bodyVariant;

    $classes = [
        'd-flex',
        'h-100',
        'w-100',
        'overflow-hidden',
        $bodyColor,
    ];
@endphp

<body {{ $attributes->class($classes) }}>
    {{ $slot }}
</body>
