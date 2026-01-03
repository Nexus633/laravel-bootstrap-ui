@props([
    'height' => null, // z.B. '20px' oder '1px'
])
@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    $field->addClass('progress')
          ->addStyleWhen($height, 'height', $height);
@endphp

<div {{ $attributes->class($field->getClasses())->merge(['style' => $field->getStyles()]) }}>
    {{ $slot }}
</div>
