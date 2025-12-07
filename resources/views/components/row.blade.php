@props([
    'g' => null,   // Gutter (Abstand) allgemein: 0-5
    'gx' => null,  // Horizontaler Abstand
    'gy' => null,  // Vertikaler Abstand
    'vAlign' => null, // Vertikale Ausrichtung: 'start', 'center', 'end'
    'hAlign' => null,   // Horizontale Ausrichtung: 'start', 'center', 'end', 'around', 'between', 'evenly'

    // NEU: Row Columns (Anzahl der Spalten pro Reihe: 1-6 oder 'auto')
    'cols' => null, // Standard (xs) -> row-cols-*
    'sm' => null,   // row-cols-sm-*
    'md' => null,   // row-cols-md-*
    'lg' => null,   // row-cols-lg-*
    'xl' => null,   // row-cols-xl-*
    'xxl' => null,  // row-cols-xxl-*
])

@php
    // Priorität: Attribut > Prop
    $vAlign = $attributes->get('align:vertical') ?? $vAlign;
    $hAlign = $attributes->get('align:horizontal') ?? $hAlign;

    // Attribute bereinigen (damit align:vertical nicht im HTML output steht)
    $attributes = $attributes->except(['align:vertical', 'align:horizontal']);

    $classes = 'row';

    if ($g !== null)  $classes .= " g-$g";
    if ($gx !== null) $classes .= " gx-$gx";
    if ($gy !== null) $classes .= " gy-$gy";

// Alignment
    if($vAlign !== null) $classes .= ' align-items-' . $vAlign;
    if($hAlign !== null) $classes .= ' justify-content-' . $hAlign;

    // NEU: Row Cols Logic
    // Wir hängen die Klasse nur an, wenn der Wert gesetzt ist
    if ($cols !== null) $classes .= " row-cols-$cols";
    if ($sm !== null)   $classes .= " row-cols-sm-$sm";
    if ($md !== null)   $classes .= " row-cols-md-$md";
    if ($lg !== null)   $classes .= " row-cols-lg-$lg";
    if ($xl !== null)   $classes .= " row-cols-xl-$xl";
    if ($xxl !== null)  $classes .= " row-cols-xxl-$xxl";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} {{ $attributes }}>
    {{ $slot }}
</div>
