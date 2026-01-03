@props([
    'variant' => null
])
@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();
    $field->addClassWhen($variant, 'table-' . $variant);

@endphp

<tr  {{ $attributes->merge(['class' => $field->getClasses()]) }}>
    {{ $slot }}
</tr>
