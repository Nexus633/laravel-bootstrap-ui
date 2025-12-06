@props([
    'divider' => null, // Optional: Eigenes Trennzeichen, z.B. '>' oder '|'
])

@php
    // Klassen Array
    $classes = ['breadcrumb'];
@endphp

<nav aria-label="breadcrumb" {{ $attributes->except('class') }}>
    <ol
        {{ $attributes->only('class')->merge(['class' => 'breadcrumb']) }}
        @if($divider) style="--bs-breadcrumb-divider: '{{ $divider }}';" @endif
    >
        {{ $slot }}
    </ol>
</nav>
