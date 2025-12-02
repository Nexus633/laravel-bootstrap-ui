@props([
    'variant' => 'primary',
    'size' => null,
    'type' => 'button',
    'loading' => null,
    'icon' => null,
    'outline' => false,
    'dismiss' => 'alert'
])

@php
    $classes = 'btn';

    // Logik für Outline vs Solid
    $actualVariant = $outline && !str_contains($variant, 'outline')
                     ? 'outline-' . $variant
                     : $variant;

    $classes .= ' btn-' . $actualVariant;

    if ($size) {
        $classes .= ' btn-' . $size;
    }

    $isLink = $attributes->has('href');
    $tag = $isLink ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if(!$isLink) type="{{ $type }}" @endif
{{ $attributes->merge(['class' => $classes]) }}
@if($loading && !$isLink)
    wire:loading.attr="disabled" wire:target="{{ $loading }}"
@endif
@if($actualVariant === 'close')
    data-bs-dismiss="{{ $dismiss }}" aria-label="Close"
@endif
>
{{-- Hier holen wir das ausgelagerte Innenleben --}}
@include('bs::components.partials.button-content')
</{{ $tag }}>
