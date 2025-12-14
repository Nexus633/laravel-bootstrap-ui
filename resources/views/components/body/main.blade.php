@php
    $classes = [
        'flex-grow-1',
        'd-flex',
        'flex-column',
        'h-100',
        'w-100',
        'overflow-hidden',
        'position-relative'
    ];
@endphp

<main {{ $attributes->class($classes) }}>
    {{ $slot }}
</main>
