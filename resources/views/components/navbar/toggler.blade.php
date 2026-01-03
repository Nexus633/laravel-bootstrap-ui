@props([
    'target', // ID des Collapse-Divs (ohne #)
])
@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $field->addClass('navbar-toggler')
          ->addData('data-bs-toggle', 'collapse')
          ->addData('data-bs-target', '#' . $target)
          ->addData('aria-controls', $target)
          ->addData('aria-expanded', 'false')
          ->addData('aria-label', 'Toggle navigation');
@endphp

<button type="button" {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
    <x-bs::text span class="navbar-toggler-icon" />
</button>
