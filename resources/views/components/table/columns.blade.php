@props([
    'footer' => false, // Wenn true, wird <tfoot> gerendert
    'variant' => null, // z.B. 'light' oder 'dark'
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $tag = $footer ? 'tfoot' : 'thead';

    // Klasse vorbereiten (oder null, wenn keine Variante)
    $field->addClassWhen($variant, 'table-' . $variant);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $field->getClasses()]) }}>
    <tr>
        {{ $slot }}
    </tr>
</{{ $tag }}>
