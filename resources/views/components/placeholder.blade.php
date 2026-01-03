@props([
    'animation' => 'glow', // 'glow' (pulsieren) oder 'wave' (welle)
    'tag' => 'div',        // div, p, span...
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    $field->addClass('placeholder-' . $animation)
          ->addData('aria-hidden', 'true');
@endphp

<{{ $tag }} {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
    {{ $slot }}
</{{ $tag }}>
