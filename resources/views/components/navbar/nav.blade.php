@props([
    'scroll' => false, // Für Scrollspy in der Nav
    'height' => null,  // Max-Height für Scroll
    'align' => null,   // 'start', 'center', 'end'
])

@php

    $start = $attributes->get('start');
    $center = $attributes->get('center');
    $end = $attributes->get('end');

    $attributes = $attributes->except(['start', 'center', 'end']);

    if($start) $align = 'start';
    if($center) $align = 'center';
    if($end) $align = 'end';

    $classes = ['navbar-nav'];

    if ($scroll) {
        $classes[] = 'navbar-nav-scroll';
    }

    // Alignment Logik (Flexbox Utilities)
    $alignClass = match ($align) {
        'start'  => 'me-auto', // "Margin End Auto" -> Drückt alles Folgende nach rechts
        'end'    => 'ms-auto', // "Margin Start Auto" -> Drückt sich selbst nach rechts
        'center' => 'mx-auto', // "Margin X Auto" -> Zentriert sich (wenn Platz da ist)
        default  => null
    };

    if ($alignClass) $classes[] = $alignClass;
@endphp

<ul
    {{ $attributes->class($classes) }}
    @if($scroll && $height) style="--bs-scroll-height: {{ $height }};" @endif
>
    {{ $slot }}
</ul>
