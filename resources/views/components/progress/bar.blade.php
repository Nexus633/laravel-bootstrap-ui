@props([
    'value' => 0,          // Prozent (0-100)
    'min' => 0,
    'max' => 100,
    'variant' => null,     // bg-primary, bg-success...
    'striped' => false,    // Gestreift
    'animated' => false,   // Bewegt sich (braucht striped)
    'label' => false,      // Zeigt die % Zahl im Balken an
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    // Berechnung für width
    $width = ($value - $min) / ($max - $min) * 100;

    // Attribute
    $field->addClass('progress-bar')
          ->addClassWhen($variant, 'bg-' . $variant)
          ->addClassWhen($striped, 'progress-bar-striped')
          ->addClassWhen($animated, 'progress-bar-animated')
          ->addData('role', 'progressbar')
          ->addData('aria-valuenow', $value)
          ->addData('aria-valuemin', $min)
          ->addData('aria-valuemax', $max)
          ->addStyle('width', $width . '%');
@endphp

<div
    {{ $attributes->class($field->getClasses())
                  ->merge($field->getDataAttributes())
                  ->merge(['style' => $field->getStyles()])
    }}
>
    @if($label)
        {{ round($width) }}%
    @else
        {{ $slot }}
    @endif
</div>
