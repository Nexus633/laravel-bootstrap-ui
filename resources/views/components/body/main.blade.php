@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    $field->addClass(
        'flex-grow-1',
        'd-flex',
        'flex-column',
        'h-100',
        'w-100',
        'overflow-hidden',
        'position-relative'
    );

@endphp

<main {{ $attributes->class($field->getClasses()) }}>
    {{ $slot }}
</main>
