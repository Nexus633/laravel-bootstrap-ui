@props([
    'href' => '#',
    'active' => false,
    'disabled' => false,
])

@php

    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $field->addClass('nav-link')
          ->addClassWhen($active, 'active')
          ->addClassWhen($disabled, 'disabled')
          ->addDataWhen($active, 'aria-current', 'page');

@endphp

<li class="nav-item">
    <a href="{{ $href }}" {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
        {{ $slot }}
    </a>
</li>
