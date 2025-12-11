@props([
    'variant' => null,
    'sticky' => false,
])

@php
    // 1. Wir bauen ein Array statt Strings zu verketten.
    // Das verhindert doppelte Leerzeichen und ist sauberer zu lesen.
    $classesArray = [
        $variant ? 'table-' . $variant : null,
        $sticky ? 'table-sticky-cell' : null,
    ];

    // 2. Wir filtern NULL Werte raus
    $classesArray = array_filter($classesArray);

    // 3. Wir machen einen String daraus ODER null (damit das Attribut ganz verschwindet)
    $classString = empty($classesArray) ? null : implode(' ', $classesArray);
@endphp

<td
        {{-- merge ignoriert keys mit value NULL komplett -> kein class="" --}}
        {{ $attributes->merge(['class' => $classString]) }}

        {{-- WICHTIG: Das Attribut für JS --}}
        @if($sticky) data-sticky="true" @endif
>
    {{ $slot }}
</td>
