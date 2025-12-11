@props([
    'size' => null,
])

@php
    $classes = ['input-group'];
    if ($size) $classes[] = 'input-group-' . $size;
@endphp

<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
