@props([
    'name',
    /*
     * Icon Größe:
     * Zahl (1-6) -> fs-{n} Klasse (Bootstrap Standard)
     * String (z.B. '4rem', '60px') -> style="font-size: ..."
     */
    'size' => null,
    'variant' => null, // primary, secondary, danger, success, etc.
    'asInput' => false,
])
@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $field = BootstrapUi::make();

    $asInput = $attributes->pluck('as:input', $asInput);

    $field->addClass(Icon::toClass($name))
          ->addClassWhen($size && is_numeric($size), 'fs-' . $size)
          ->addClassWhen($variant, 'text-' . $variant)
          ->addStyleWhen($size && !is_numeric($size), 'font-size', $size);
@endphp


@if($asInput)
    <x-bs::input-group.text>
        <i {{ $attributes->merge(['class' => $field->getClasses(), 'style' => $field->getStyles()]) }} aria-hidden="true"></i>
    </x-bs::input-group.text>
@else
    <i {{ $attributes->merge(['class' => $field->getClasses(), 'style' => $field->getStyles()]) }} aria-hidden="true"></i>
@endif
