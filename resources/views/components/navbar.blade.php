@props([
    'expand' => 'lg',
    'sticky' => null,
    'fixed' => null,
    'container' => 'fluid', // fluid, sm, md... oder false
    'theme' => null,
    'bg' => null,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    $field->addClass('navbar')
          ->addClassWhen($expand, 'navbar-expand-' . $expand)
          ->addClassWhen($sticky, 'sticky-' . $sticky)
          ->addClassWhen($fixed, 'fixed-' . $fixed)
          ->addClassWhen($bg, 'bg-' . $bg, 'bg-body-tertiary')
          ->addDataWhen($theme, 'data-bs-theme', $theme);

    $isFluid = ($container === 'fluid');
    $sizeVal = (!$isFluid) ? $container : null;
@endphp

<nav {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
    @if($container)
        <x-bs::container :fluid="$isFluid" :size="$sizeVal">
            {{ $slot }}
        </x-bs::container>
    @else
        {{ $slot }}
    @endif
</nav>
