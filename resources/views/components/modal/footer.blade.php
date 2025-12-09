@props([
    'align' => 'end',   // Standard Fallback (Bootstrap Standard ist rechts)
                        // 'start', 'center', 'end', 'between', 'around', 'evenly'
])

@php
    // 1. Boolean Flags abfragen
    // Das erlaubt <x-bs::modal.footer start ...>
    $start   = $attributes->get('start');
    $center  = $attributes->get('center');
    $end     = $attributes->get('end');
    $between = $attributes->get('between');
    $around  = $attributes->get('around');
    $evenly  = $attributes->get('evenly'); // Bootstrap 5 Feature

    // 2. Attribute säubern
    // WICHTIG: Damit 'start="true"' nicht als invalides Attribut im HTML div landet
    $attributes = $attributes->except(['start', 'center', 'end', 'between', 'around', 'evenly']);

    // 3. Priorisierung
    // Wenn ein Boolean Flag gesetzt ist, überschreibt es das 'align' Prop.
    // Die Reihenfolge hier bestimmt die Priorität (letztes gewinnt, falls jemand start UND center schreibt).
    if ($start)   $align = 'start';
    if ($center)  $align = 'center';
    if ($end)     $align = 'end';
    if ($between) $align = 'between';
    if ($around)  $align = 'around';
    if ($evenly)  $align = 'evenly';

    // 4. Mapping auf Bootstrap Klassen
    $justifyMap = [
        'start'   => 'justify-content-start',
        'center'  => 'justify-content-center',
        'end'     => 'justify-content-end',
        'between' => 'justify-content-between',
        'around'  => 'justify-content-around',
        'evenly'  => 'justify-content-evenly',
    ];

    $alignClass = $justifyMap[$align] ?? 'justify-content-end';
@endphp

<div {{ $attributes->merge(['class' => 'modal-footer ' . $alignClass]) }}>
    {{ $slot }}
</div>
