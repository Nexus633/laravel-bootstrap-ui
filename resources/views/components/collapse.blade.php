@props([
    'id',                  // PFLICHT: Damit der Button es findet
    'show' => false,       // true = Standardmäßig offen
    'horizontal' => false, // true = Slide von links nach rechts
])

@php
    $classes = [
        'collapse',
        'collapse-horizontal' => $horizontal,
        'show' => $show
    ];
@endphp

<div id="{{ $id }}" {{ $attributes->except('id')->class($classes) }}>
    {{-- 
        Bei horizontalem Collapse benötigt Bootstrap einen inneren Container 
        mit fester Breite (width), damit die Animation flüssig ist.
        Wir lassen den Slot "raw", damit der User volle Kontrolle hat, 
        aber dies ist ein wichtiger Hinweis für die Nutzung.
    --}}
    {{ $slot }}
</div>
