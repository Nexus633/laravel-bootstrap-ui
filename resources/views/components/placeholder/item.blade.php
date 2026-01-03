@props([
    'cols' => null,      // 1-12
    'width' => null,     // z.B. '75%'
    'size' => null,      // xs, sm, lg
    'variant' => null,   // primary, secondary...
    'button' => false,   // Button-Look
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $field->addClass('placeholder')
          ->addClassWhen($cols, 'col-' . $cols)
          ->addClassWhen($size, 'placeholder-' . $size)
          ->addClassWhen($variant, 'bg-' . $variant)
          ->addClassWhen($button, ['btn', 'disabled'])
          ->addStyleWhen($width, 'width', $width)
          ->addDataWhen($button, 'href', '#')
          ->addDataWhen($button, 'tabindex', '-1');

    // Tag bestimmen: 'a' für Buttons (damit disabled styling greift), sonst 'span'
    $tag = $button ? 'a' : 'span';
@endphp

<{{ $tag }}
    {{ $attributes->class($field->getClasses())
                  ->merge($field->getDataAttributes())
                  ->merge(['style' => $field->getStyles()])
    }}
></{{ $tag }}>
