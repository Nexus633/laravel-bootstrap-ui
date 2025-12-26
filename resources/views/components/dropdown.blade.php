@props([
    'label' => null,
    'icon' => null,
    'variant' => 'secondary',
    'size' => null,
    'align' => 'start',
    'noCaret' => false,
    'direction' => null,
    'nav' => false, // NEU: Schaltet in den Navbar-Modus
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    $noCaret = $attributes->get('no:caret', $noCaret);
    $iconClass = Icon::toClass($icon);

    // --- 1. MODUS ENTSCHEIDUNG ---
    if ($nav) {
        // A. Navbar Modus
        $wrapperTag = 'li';
        // Nav Items sind fast immer 'dropdown', Richtungen wie 'dropup' sind in Navbars selten/anders
        $wrapperClasses = ['nav-item', 'dropdown'];

        $triggerTag = 'a';
        $triggerClasses = ['nav-link'];
        $triggerAttrs = ['href' => '#', 'role' => 'button'];

    } else {
        // B. Button Modus (Dein alter Code)
        $wrapperTag = 'div';

        // Richtungs-Logik (nur für Buttons relevant)
        $dirClass = match ($direction){
            'up' => 'dropup',
            'up-center' => 'dropup-center dropup',
            'left' => 'dropstart',
            'right' => 'dropend',
            'center' => 'dropdown-center',
            default => 'dropdown'
        };
        $wrapperClasses = [$dirClass];

        $triggerTag = 'button';
        $triggerClasses = ['btn', 'btn-' . $variant];
        if ($size) $triggerClasses[] = 'btn-' . $size;
        $triggerAttrs = ['type' => 'button'];
    }

    // --- 2. GEMEINSAME LOGIK ---

    // Toggle Pfeil Logik
    if (!$noCaret) {
        $triggerClasses[] = 'dropdown-toggle';
    }

    // Menü Ausrichtung
    $menuClasses = ['dropdown-menu', 'shadow'];
    if ($align === 'end') $menuClasses[] = 'dropdown-menu-end';

    // Attribute bereinigen
    $attributes = $attributes->except(['no:caret']);
@endphp

<{{ $wrapperTag }} class="{{ implode(' ', $wrapperClasses) }}">
    <{{ $triggerTag }}
        {{-- Attribute wie id, wire:click etc. kommen hier rein --}}
        {{ $attributes->merge(['class' => implode(' ', $triggerClasses)]) }}

        {{-- Feste Attribute für Dropdown Funktion --}}
        data-bs-toggle="dropdown"
        aria-expanded="false"

        {{-- Dynamische Attribute (href vs type) --}}
        @foreach($triggerAttrs as $key => $val) {{ $key }}="{{ $val }}" @endforeach
    >
    @if($iconClass)
        <i class="bi {{ $iconClass }} @if($label) me-1 @endif"></i>
    @endif
    {{ $label }}
</{{ $triggerTag }}>

<ul class="{{ implode(' ', $menuClasses) }}">
    {{ $slot }}
</ul>

</{{ $wrapperTag }}>
