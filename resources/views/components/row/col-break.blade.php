@props([
    // NEU: Row Columns (Anzahl der Spalten pro Reihe: 1-6 oder 'auto')
    'sm' => null,   // d-none d-sd-block
    'md' => null,   // d-none d-md-block
    'lg' => null,   // d-none d-lg-block
    'xl' => null,   // d-none d-xl-block
    'xxl' => null,  // d-none d-xxl-block
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $hasSize = $sm || $md || $lg || $xl || $xxl;
    $field->addClass('w-100')
          ->addClassWhen($hasSize, 'd-none')
          ->addClassWhen($sm, 'd-sm-block')
          ->addClassWhen($md, 'd-md-block')
          ->addClassWhen($lg, 'd-lg-block')
          ->addClassWhen($xl, 'd-xl-block')
          ->addClassWhen($xxl, 'd-xxl-block');
@endphp

<div {{ $attributes->class($field->getClasses()) }}></div>
