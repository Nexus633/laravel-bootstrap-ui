@props([
    'fluid' => false,  // Boolean Flag für 100% Breite
    'size' => null,    // Optional: sm, md, lg, xl, xxl
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $field->addClassWhen(!$fluid && !$size, 'container')
          ->addClassWhen($fluid && !$size, 'container-fluid')
          ->addClassWhen(!$fluid && $size, 'container-' . $size);

@endphp

<div {{ $attributes->class($field->getClasses()) }}>
    {{ $slot }}
</div>
