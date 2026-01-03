@props([
    'target',            // Die ID der Navigation (Pflicht!)
    'height' => '500px', // Höhe des scrollbaren Bereichs
    'smooth' => true,    // Weiches Scrollen
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $field->addClass('scrollspy-example', 'bg-body-tertiary', 'p-3', 'rounded-2')
          ->addData('data-bs-spy', 'scroll')
          ->addData('data-bs-target', '#' . $target)
          ->addDataWhen($smooth, 'data-bs-smooth-scroll', 'true', 'false')
          ->addData('tabindex', '0')
          ->addStyle('height', $height)
          ->addStyle('overflow-y', 'scroll')
          ->addStyle('position', 'relative');
@endphp

<div {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes())->merge(['style' => $field->getStyles()]) }}>
    {{ $slot }}
</div>
