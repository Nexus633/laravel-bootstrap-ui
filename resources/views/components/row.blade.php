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
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();

    // Priorität: Attribut > Prop
    $vAlign = $attributes->pluck('align:vertical', $vAlign);
    $hAlign = $attributes->pluck('align:horizontal', $hAlign);

    $field->addClass('row')
          ->addClassWhen($g, 'g-' . $g)
          ->addClassWhen($gx, 'gx-' . $gx)
          ->addClassWhen($gy, 'gy-' . $gy)
          // Alignment
          ->addClassWhen($vAlign, 'align-items-' . $vAlign)
          ->addClassWhen($hAlign, 'justify-content-' . $hAlign)
          // Row Cols Logic
          ->addClassWhen($cols, 'row-cols-' . $cols)
          ->addClassWhen($sm, 'row-cols-sm-' . $sm)
          ->addClassWhen($md, 'row-cols-md-' . $md)
          ->addClassWhen($lg, 'row-cols-lg-' . $lg)
          ->addClassWhen($xl, 'row-cols-xl-' . $xl)
          ->addClassWhen($xxl, 'row-cols-xxl-' . $xxl);

@endphp

<div {{ $attributes->class($field->getClasses()) }}>
    {{ $slot }}
</div>
