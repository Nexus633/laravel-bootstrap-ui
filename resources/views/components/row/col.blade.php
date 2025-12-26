@props([
    'size' => null,     // Standardgröße (xs) oder feste Breite
    'sm' => null,       // ≥ 576px
    'md' => null,       // ≥ 768px
    'lg' => null,       // ≥ 992px
    'xl' => null,       // ≥ 1200px
    'xxl' => null,      // ≥ 1400px
    'order' => null,    // 0-5, first, last
    'vAlign' => null,   // Vertikale Ausrichtung: 'start', 'center', 'end'
])

@php
    $vAlign = $attributes->get('align:vertical', $vAlign);
    $attributes = $attributes->except('align:vertical');

    $classes = [];

    $hasSize = $size || $sm || $md || $lg || $xl || $xxl;

    if (!$hasSize) {
        $classes[] = 'col';
    } else {
        if ($size !== null) $classes[] = "col-$size";
        if ($sm !== null)   $classes[] = "col-sm-$sm";
        if ($md !== null)   $classes[] = "col-md-$md";
        if ($lg !== null)   $classes[] = "col-lg-$lg";
        if ($xl !== null)   $classes[] = "col-xl-$xl";
        if ($xxl !== null)  $classes[] = "col-xxl-$xxl";
    }

    // Neu: Order Logik
    if ($order !== null) {
        // Erlaubt: order="1", order="first", order="last"
        $classes[] = "order-$order";
    }

    // Align Self (Vertikal für DIESE Column)
    if($vAlign !== null){
        $classes[] = 'align-self-' . $vAlign;
    }

@endphp

<div {{ $attributes->class($classes) }} {{ $attributes }}>
    {{ $slot }}
</div>
