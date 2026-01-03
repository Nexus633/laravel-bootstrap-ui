@props([
    'size' => null,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();
    $field->addClass('input-group')
          ->addClassWhen($size, 'input-group-' . $size);
@endphp

<div {{ $attributes->class($field->getClasses()) }}>
    {{ $slot }}
</div>
