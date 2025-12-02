@props([
    'fluid' => false,  // Boolean Flag für 100% Breite
    'size' => null,    // Optional: sm, md, lg, xl, xxl
])

@php
    $class = 'container';

    if ($fluid) {
        $class = 'container-fluid';
    } elseif ($size) {
        // Erzeugt z.B. container-lg
        $class = 'container-' . $size;
    }
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</div>
