@props([
    'label' => null,         // Text auf dem Button (optional)
    'icon' => null,          // Icon (z.B. bi-three-dots-vertical)
    'variant' => 'secondary',// Button Farbe
    'size' => null,          // sm, lg
    'align' => 'start',      // start, end (für Ausrichtung des Menüs)
    'noCaret' => false,      // Pfeil ausblenden (gut für reine Icon-Buttons)
    'direction' => null      // Ausrichtung des Menüs up, down, left, right, center
])

@php
    $noCaret = $attributes->get('no:caret') ?? $noCaret;
    $btnClasses = 'btn';

    $btnClasses .= ' btn-' . $variant;
    if ($size) $btnClasses .= ' btn-' . $size;
    // Wenn noCaret an ist, entfernen wir den Pfeil via CSS-Klasse (Bootstrap Utility oder Custom)
    // Bootstrap hat dafür keine native Klasse, aber wir können den Pfeil im CSS verstecken
    // oder einfach akzeptieren, dass er da ist.
    // Ein gängiger Trick ist 'dropdown-toggle-split' zu missbrauchen oder Custom CSS.
    // Wir lassen ihn erst mal Standard.
        if (!$noCaret) {
        $btnClasses .= ' dropdown-toggle';
    }


    $menuClasses = 'dropdown-menu shadow'; // Shadow sieht immer besser aus
    if ($align === 'end') $menuClasses .= ' dropdown-menu-end';
    $dropdownClass = match ($direction){
        'up' => 'dropup',
        'up-center' => 'dropup-center dropup',
        'left' => 'dropstart',
        'right' => 'dropend',
        'center' => 'dropdown-center',
        default => 'dropdown'
    };

    $attributes->except(['no:caret']);
@endphp

<div class="{{ $dropdownClass }}">
    <button
            type="button"
            {{ $attributes->merge(['class' => $btnClasses]) }}
            data-bs-toggle="dropdown"
            aria-expanded="false"
            @if($noCaret) style="background-image: none;" @endif
    >
        @if($icon)
            <i class="bi {{ $icon }} @if($label) me-1 @endif"></i>
        @endif
        {{ $label }}
    </button>

    <ul class="{{ $menuClasses }}">
        {{ $slot }}
    </ul>
</div>
