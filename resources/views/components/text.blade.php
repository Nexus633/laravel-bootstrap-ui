@props([
    'as' => null,
    'variant' => null,

    // Struktur
    'h1' => false, 'h2' => false, 'h3' => false, 'h4' => false, 'h5' => false, 'h6' => false,
    'p' => false, 'span' => false, 'div' => false, 'label' => false,
    'small' => false, 'mark' => false, 'code' => false, 'lead' => false,
    'blockquote' => false,

    // Display
    'display1' => false, 'display2' => false, 'display3' => false,
    'display4' => false, 'display5' => false, 'display6' => false,

    // Weight
    'bold' => false, 'bolder' => false, 'semibold' => false, 'medium' => false,
    'normal' => false, 'light' => false, 'italic' => false,

    // Transform / Decoration
    'uppercase' => false, 'lowercase' => false, 'capitalize' => false,
    'underline' => false, 'lineThrough' => false, 'noDecoration' => false,

    // Utilities
    'truncate' => false, 'nowrap' => false, 'break' => false,
    'center' => false, 'end' => false, 'start' => false,
])

@php
    // ---------------------------------------------------------
    // 1. TAG LOGIK (First wins - Semantische Hierarchie)
    // ---------------------------------------------------------
    $tag = $as ?? 'p';

    if (!$as) {
        // Überschriften haben Vorrang
        if ($h1) $tag = 'h1';
        elseif ($h2) $tag = 'h2';
        elseif ($h3) $tag = 'h3';
        elseif ($h4) $tag = 'h4';
        elseif ($h5) $tag = 'h5';
        elseif ($h6) $tag = 'h6';
        elseif ($display1 || $display2 || $display3 || $display4 || $display5 || $display6) $tag = 'h1';
        // Spezielle Elemente
        elseif ($blockquote) $tag = 'blockquote';
        elseif ($code) $tag = 'code';
        elseif ($mark) $tag = 'mark';
        elseif ($label) $tag = 'label'; // label tag
        // Text Container
        elseif ($small) $tag = 'small';
        elseif ($span) $tag = 'span';
        elseif ($div) $tag = 'div';
        // Lead ist nur eine Klasse, bleibt p
        elseif ($lead) $tag = 'p';
    }

    // ---------------------------------------------------------
    // 2. KLASSEN LOGIK (Hierarchische Ordnung)
    // ---------------------------------------------------------
    $classes = [];

    // A. Farbe
    if ($variant) {
        $classes[] = "text-{$variant}";
    }

    // B. Display Sizes (Größer gewinnt gegen Kleiner - falls jemand display1 und display4 setzt)
    $displayClass = null;
    if ($display6) $displayClass = 'display-6';
    if ($display5) $displayClass = 'display-5';
    if ($display4) $displayClass = 'display-4';
    if ($display3) $displayClass = 'display-3';
    if ($display2) $displayClass = 'display-2';
    if ($display1) $displayClass = 'display-1'; // Display 1 ist "Boss"
    if ($displayClass) $classes[] = $displayClass;

    // C. Font Weight (Logik: Von Leicht nach Schwer)
    // Wer weiter unten steht, überschreibt die oberen.
    $weightClass = null;
    if ($light)    $weightClass = 'fw-light';    // 300
    if ($normal)   $weightClass = 'fw-normal';   // 400
    if ($medium)   $weightClass = 'fw-medium';   // 500
    if ($semibold) $weightClass = 'fw-semibold'; // 600
    if ($bold)     $weightClass = 'fw-bold';     // 700
    if ($bolder)   $weightClass = 'fw-bolder';   // 800+
    if ($weightClass) $classes[] = $weightClass;

    // D. Transformation (Logik: Uppercase ist meist dominanter Stil)
    $transformClass = null;
    if ($lowercase)  $transformClass = 'text-lowercase';
    if ($capitalize) $transformClass = 'text-capitalize';
    if ($uppercase)  $transformClass = 'text-uppercase';
    if ($transformClass) $classes[] = $transformClass;

    // E. Decoration (Logik: "None" sollte gewinnen, um z.B. Links zu resetten)
    $decorationClass = null;
    if ($underline)    $decorationClass = 'text-decoration-underline';
    if ($lineThrough)  $decorationClass = 'text-decoration-line-through';
    if ($noDecoration) $decorationClass = 'text-decoration-none';
    if ($decorationClass) $classes[] = $decorationClass;

    // F. Alignment
    $alignClass = null;
    if ($start)  $alignClass = 'text-start';
    if ($end)    $alignClass = 'text-end';
    if ($center) $alignClass = 'text-center'; // Center ist oft das explizit gewollte Override
    if ($alignClass) $classes[] = $alignClass;

    // G. Utilities
    if ($italic)   $classes[] = 'fst-italic';
    if ($truncate) $classes[] = 'text-truncate';
    if ($nowrap)   $classes[] = 'text-nowrap';
    if ($break)    $classes[] = 'text-break';
    if ($lead)     $classes[] = 'lead';

    // Optional
    if ($code) $classes[] = 'code-bg';
    if ($blockquote) $classes[] = 'blockquote';

@endphp

<{{ $tag }} {{ $attributes->class($classes) }}>
{{ $slot }}
</{{ $tag }}>
