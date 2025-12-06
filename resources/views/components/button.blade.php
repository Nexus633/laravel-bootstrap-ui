@props([
    'variant' => 'primary',
    'size' => null,
    'type' => 'button',
    'loading' => null,
    'icon' => null,
    'outline' => false,
    'dismiss' => null,
    'relative' => false, // NEU: Damit Badges positioniert werden können
    'align' => null         // NEU: 'right', 'block', 'center'
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;
    $id = $attributes->get('id') ?? 'btn-' . uniqid();

    // Basis-Klasse
    $classes = 'btn';

    // Logik für Outline vs Solid
    $actualVariant = $outline && !str_contains($variant, 'outline')
                     ? 'outline-' . $variant
                     : $variant;

    $classes .= ' btn-' . $actualVariant;

    if ($size) {
        $classes .= ' btn-' . $size;
    }

    // NEU: Position Relative für Badges
    if ($relative) {
        $classes .= ' position-relative';
    }

    // --- NEU: ALIGNMENT LOGIC ---
    if ($align === 'right') {
        // float-end ist der klassische Weg in Block-Containern (wie Card-Header)
        $classes .= ' float-end';
    } elseif ($align === 'block') {
        // Volle Breite
        $classes .= ' w-100 d-block';
    } elseif ($align === 'center') {
        // Zentriert (braucht d-block, da Buttons standardmäßig inline sind)
        $classes .= ' d-block mx-auto';
    }

    $isLink = $attributes->has('href');
    $tag = $isLink ? 'a' : 'button';
    $icon = Icon::toClass($icon);
@endphp

<{{ $tag }} id="{{ $id }}"
@if(!$isLink) type="{{ $type }}" @endif
{{ $attributes->merge(['class' => $classes]) }}
@if($loading && !$isLink)
    wire:loading.attr="disabled" wire:target="{{ $loading }}"
@endif
@if($actualVariant === 'close')
    data-bs-dismiss="{{ $dismiss }}" aria-label="Close"
@endif
>
{{-- Dein ausgelagertes Innenleben --}}
@include('bs::components.partials.button-content')
</{{ $tag }}>
