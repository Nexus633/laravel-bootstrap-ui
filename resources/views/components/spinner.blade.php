@props([
    'type' => 'border', // 'border' (Kreis) oder 'grow' (Pulsieren)
    'variant' => null,  // primary, secondary, light...
    'size' => null,     // 'sm' (klein) oder null (normal)
    'label' => 'Loading...' // Für Screenreader (Barrierefreiheit)
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    $field->addClass('spinner-' . $type)
          ->addClassWhen($variant, 'text-' . $variant)
          ->addClassWhen($size, 'spinner-' . $type . '-' . $size)
          ->addData('role', 'status')
          ->addData('aria-hidden', 'true');
@endphp

<div {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
    <span class="visually-hidden">{{ $label }}</span>
</div>
