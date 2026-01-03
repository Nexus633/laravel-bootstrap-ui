@props([
    'as' => null,
    'variant' => null,
    'size' => null,
    'h1' => false, 'h2' => false, 'h3' => false, 'h4' => false, 'h5' => false, 'h6' => false,
    'p' => false, 'span' => false, 'div' => false, 'label' => false,
    'small' => false, 'mark' => false, 'code' => false, 'lead' => false,
    'blockquote' => false,
    'display1' => false, 'display2' => false, 'display3' => false,
    'display4' => false, 'display5' => false, 'display6' => false,
    'bold' => false, 'bolder' => false, 'semibold' => false, 'medium' => false,
    'normal' => false, 'light' => false, 'italic' => false,
    'uppercase' => false, 'lowercase' => false, 'capitalize' => false,
    'underline' => false, 'lineThrough' => false, 'noDecoration' => false,
    'truncate' => false, 'nowrap' => false, 'break' => false,
    'center' => false, 'end' => false, 'start' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();

    // --- 1. TAG LOGIC ---
    $tag = $as ?? 'p';
    if (!$as) {
        if ($h1 || $display1 || $display2 || $display3 || $display4 || $display5 || $display6) $tag = 'h1';
        elseif ($h2) $tag = 'h2';
        elseif ($h3) $tag = 'h3';
        elseif ($h4) $tag = 'h4';
        elseif ($h5) $tag = 'h5';
        elseif ($h6) $tag = 'h6';
        elseif ($blockquote) $tag = 'blockquote';
        elseif ($code) $tag = 'code';
        elseif ($mark) $tag = 'mark';
        elseif ($label) $tag = 'label';
        elseif ($small) $tag = 'small';
        elseif ($span) $tag = 'span';
        elseif ($div) $tag = 'div';
        elseif ($lead) $tag = 'p';
    }

    // --- 2. CLASS LOGIC ---
    $field->addClassWhen($variant, "text-{$variant}")
          ->addClassWhen($display1, 'display-1')->addClassWhen($display2, 'display-2')->addClassWhen($display3, 'display-3')
          ->addClassWhen($display4, 'display-4')->addClassWhen($display5, 'display-5')->addClassWhen($display6, 'display-6')
          ->addClassWhen($light, 'fw-light')->addClassWhen($normal, 'fw-normal')->addClassWhen($medium, 'fw-medium')
          ->addClassWhen($semibold, 'fw-semibold')->addClassWhen($bold, 'fw-bold')->addClassWhen($bolder, 'fw-bolder')
          ->addClassWhen($lowercase, 'text-lowercase')->addClassWhen($capitalize, 'text-capitalize')->addClassWhen($uppercase, 'text-uppercase')
          ->addClassWhen($underline, 'text-decoration-underline')->addClassWhen($lineThrough, 'text-decoration-line-through')->addClassWhen($noDecoration, 'text-decoration-none')
          ->addClassWhen($start, 'text-start')->addClassWhen($end, 'text-end')->addClassWhen($center, 'text-center')
          ->addClassWhen($italic, 'fst-italic')->addClassWhen($truncate, 'text-truncate')->addClassWhen($nowrap, 'text-nowrap')
          ->addClassWhen($break, 'text-break')->addClassWhen($lead, 'lead')->addClassWhen($code, 'code-bg')
          ->addClassWhen($blockquote, 'blockquote')->addClassWhen($size, 'fs-' . $size);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $field->getClasses()]) }}>
    {{ $slot }}
</{{ $tag }}>
