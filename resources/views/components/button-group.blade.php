@props([
    'size' => null,      // 'sm', 'lg'
    'vertical' => false, // true für vertikale Stapelung
    'toolbar' => false,  // true für Toolbar-Modus (Container für mehrere Groups)
    'label' => null,     // Wichtig für Screenreader (aria-label)
])

@php
    // Basis-Logik: Toolbar vs. Group
    if ($toolbar) {
        $baseClass = 'btn-toolbar';
        $role = 'toolbar';
    } else {
        $baseClass = $vertical ? 'btn-group-vertical' : 'btn-group';
        $role = 'group';
    }

    $classes = [$baseClass];

    // Größen-Klasse hinzufügen (gilt nicht für Toolbars, nur für Groups)
    if ($size && !$toolbar) {
        $classes[] = 'btn-group-' . $size;
    }
@endphp

<div
        role="{{ $role }}"
        @if($label) aria-label="{{ $label }}" @endif
        {{ $attributes->class($classes) }}
>
    {{ $slot }}
</div>
