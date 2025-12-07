@props([
    // NEU: Row Columns (Anzahl der Spalten pro Reihe: 1-6 oder 'auto')
    'sm' => null,   // d-none d-sd-block
    'md' => null,   // d-none d-md-block
    'lg' => null,   // d-none d-lg-block
    'xl' => null,   // d-none d-xl-block
    'xxl' => null,  // d-none d-xxl-block
])

@php
    $classes = 'w-100';

    if ($sm !== null)   $classes .= " d-none d-sm-block";
    if ($md !== null)   $classes .= " d-none d-md-block";
    if ($lg !== null)   $classes .= " d-none d-lg-block";
    if ($xl !== null)   $classes .= " d-none d-xl-block";
    if ($xxl !== null)  $classes .= " d-none d-xxl-block";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}></div>
