@props([
    'divider' => null, // Optional: Eigenes Trennzeichen, z.B. '>' oder '|'
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    $field->addClass('breadcrumb')
          ->addStyleWhen($divider, '--bs-breadcrumb-divider', "'".$divider."'");
@endphp

<nav aria-label="breadcrumb" {{ $attributes->except('class') }}>
    <ol {{ $attributes->only(['class', 'style'])->merge(['class' => $field->getClasses(), 'style' => $field->getStyles()]) }}>
        {{ $slot }}
    </ol>
</nav>
