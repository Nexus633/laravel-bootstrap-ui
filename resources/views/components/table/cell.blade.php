@props([
    'variant' => null,
    'sticky' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    // 1. Wir bauen ein Array statt Strings zu verketten.
    // Das verhindert doppelte Leerzeichen und ist sauberer zu lesen.

    $field->addClassWhen($variant, 'table-' . $variant)
          ->addClassWhen($sticky, 'table-sticky-cell')
          ->addDataWhen($sticky, 'data-sticky', 'true');
@endphp

<td
    {{-- merge ignoriert keys mit value NULL komplett -> kein class="" --}}
    {{ $attributes->merge(['class' => $field->getClasses()])->merge($field->getDataAttributes()) }}
>
    {{ $slot }}
</td>
