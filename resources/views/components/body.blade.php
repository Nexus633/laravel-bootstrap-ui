@props([
    'bodyVariant' => 'tertiary'
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();

    $bodyVariant = $attributes->pluck('body:variant', $bodyVariant);

    $field->addClass('bg-body-' . $bodyVariant)
          ->addClass('d-flex')
          ->addClass('h-100')
          ->addClass('w-100')
          ->addClass('overflow-hidden');
@endphp

<body {{ $attributes->class($field->getClasses()) }}>
    {{ $slot }}
</body>
