@props([
    'size' => null,      // 'sm', 'lg'
    'vertical' => false, // true für vertikale Stapelung
    'toolbar' => false,  // true für Toolbar-Modus (Container für mehrere Groups)
    'label' => null,     // Wichtig für Screenreader (aria-label)
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();

    $field->addClassWhen($toolbar, 'btn-toolbar', )
          ->addClassWhen(!$toolbar, $vertical ? 'btn-group-vertical' : 'btn-group')
          ->addClassWhen($size && !$toolbar, 'btn-group-' . $size)
          ->addDataWhen($toolbar, 'role', 'toolbar', 'group')
          ->addDataWhen($label, 'aria-label', $label);
@endphp

<div {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
    {{ $slot }}
</div>
