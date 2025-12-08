@props([
    'src' => null,
    'alt' => '',
    'name' => null,
    'size' => 'md',
    'shape' => 'circle',
    'variant' => 'secondary',
    'border' => false,

    // Tooltip Props
    'tooltip' => null,
    'placement' => 'top',       // top, right, bottom, left
])

@php
    // --- 0. OPTIONEN LESEN ---
    // Prüfen, ob initials:single gesetzt ist
    $singleInitial = $attributes->has('initials:single');
    // Attribut entfernen, damit es nicht im HTML landet
    $attributes = $attributes->except('initials:single');

    // --- 1. INITIALEN LOGIK ---
    $initials = '';
    if (!$src && $name) {
        if ($singleInitial) {
            // Nur der allererste Buchstabe des gesamten Strings
            $initials = strtoupper(mb_substr($name, 0, 1));
        } else {
            // Standard: Erste Buchstaben der ersten beiden Wörter (Max Mustermann -> MM)
            $parts = explode(' ', $name);
            foreach ($parts as $index => $part) {
                if ($index < 2 && !empty($part)) {
                    $initials .= strtoupper(mb_substr($part, 0, 1));
                }
            }
        }
    }

    // --- 2. AUTO-COLOR LOGIK ---
    if ($variant === 'auto') {
        // Palette verfügbarer Farben (ohne 'light', da weißer Text dort nicht lesbar ist)
        $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark', 'indigo', 'purple', 'pink', 'teal'];

        // Wir berechnen einen Hash aus dem Namen (z.B. crc32).
        // Das sorgt dafür, dass "Max" IMMER Rot ist und "Lisa" IMMER Grün.
        // Es ist also "zufällig", aber deterministisch (bleibt beim Reload gleich).
        $seed = $name ?? 'default';
        $hash = crc32($seed);

        // Modulo-Operator wählt einen Index aus dem Array
        $variant = $colors[$hash % count($colors)];
    }

    // --- 3. GRÖSSEN LOGIK ---
    $sizes = [
        'xs' => ['size' => '24px', 'font' => '0.75rem'],
        'sm' => ['size' => '32px', 'font' => '0.875rem'],
        'md' => ['size' => '48px', 'font' => '1.25rem'],
        'lg' => ['size' => '64px', 'font' => '1.75rem'],
        'xl' => ['size' => '96px', 'font' => '2.5rem'],
    ];

    $config = $sizes[$size] ?? $sizes['md'];
    $dimension = $config['size'];
    $fontSize = $config['font'];

    // --- 4. KLASSEN & STYLES ---
    $shapes = [
        'circle'  => 'rounded-circle',
        'square'  => 'rounded-0',
        'rounded' => 'rounded',
        'top'     => 'rounded-top',
        'bottom'  => 'rounded-bottom',
        'start'   => 'rounded-start',
        'end'     => 'rounded-end',
    ];
    $shapeClass = $shapes[$shape] ?? 'rounded-circle';

   $classes = [
        'd-inline-flex',
        'align-items-center',
        'justify-content-center',
        'flex-shrink-0',
        'align-middle',
        $shapeClass,
    ];

    if ($border) $classes[] = 'border border-2 border-white';
    if (!$src) $classes[] = 'text-bg-' . $variant;

    $styles = "width: {$dimension}; height: {$dimension}; font-size: {$fontSize};";

    // --- ALPINE TOOLTIP LOGIK ---
    $alpineAttrs = [];

    if ($tooltip) {
        $alpineAttrs['x-data'] = '{}';

        // 1. Placement als Data-Attribut (liest unsere Directive aus)
        $alpineAttrs['data-bs-placement'] = $placement;

        // 2. x-tooltip mit dem Text.
        // WICHTIG: Wir müssen den String in Hochkommas packen für JS: 'Text'
        // addslashes verhindert Fehler bei Namen wie "O'Connor"
        $safeTooltip = addslashes($tooltip);
        $alpineAttrs['x-tooltip'] = "'{$safeTooltip}'";

        // Optional: Element fokussierbar machen für Tastatur-Nutzer
        $alpineAttrs['tabindex'] = '0';
    }
@endphp

@if($src)
    @if($tooltip)
        <x-bs::tooltip text="{{$tooltip}}" placement="{{$placement}}">
            <img
                src="{{ $src }}"
                alt="{{ $alt ?? $name }}"
                {{ $attributes->class($classes)->merge(['style' => $styles . ' object-fit: cover;']) }}
            >
        </x-bs::tooltip>
    @else
        <img
            src="{{ $src }}"
            alt="{{ $alt ?? $name }}"
            {{ $attributes->class($classes)->merge(['style' => $styles . ' object-fit: cover;']) }}
        >
    @endif
@else
    @if($tooltip)
        <x-bs::tooltip text="{{$tooltip}}" placement="{{$placement}}">
            <div {{ $attributes->class($classes)->merge(['style' => $styles . ' user-select: none;']) }} {{$attributes}}>
                @if($initials)
                    {{ $initials }}
                @else
                    <x-bs::icon name="person-fill" />
                @endif
            </div>
        </x-bs::tooltip>
    @else
        <div {{ $attributes->class($classes)->merge(['style' => $styles . ' user-select: none;']) }} {{$attributes}}>
            @if($initials)
                {{ $initials }}
            @else
                <x-bs::icon name="person-fill" />
            @endif
        </div>
    @endif
@endif
