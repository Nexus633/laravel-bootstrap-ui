@props([
    'hash', // z.B. 'section-1' (ohne #)
])

<a
    href="#{{ $hash }}"
    {{ $attributes->class(['list-group-item', 'list-group-item-action']) }}
>
    {{ $slot }}
</a>
