@props([
    'variant' => 'danger',      // Farbe (danger, primary, etc.)
    'pill' => false,         // Runde Ecken (rounded-pill)
    'dot' => false,          // Kleiner Punkt ohne Text
    'border' => false,       // Weißer Rand zur Abgrenzung
    'position' => null       // top-start, top-end, bottom-start, bottom-end
])

@php
    // Mapping deiner gewünschten Positionen auf Bootstrap-Klassen
    $positions = [
        'top-start'    => 'top-0 start-0',
        'top-end'      => 'top-0 start-100',
        'bottom-start' => 'top-100 start-0',
        'bottom-end'   => 'top-100 start-100',
    ];

    $classes = $attributes->class([
        // Basis
        'badge' => !$dot,
        'p-2 rounded-circle' => $dot, // Dot braucht Padding um rund zu sein

        // Farben (Text-Bg für Kontrast bei Text, nur BG bei Dots)
        ($dot ? 'bg-' : 'text-bg-') . $variant,

        // Styles
        'rounded-pill' => $pill && !$dot,
        'border border-light' => $border,

        // Positioning Logic
        'position-absolute translate-middle' => $position,
        $positions[$position] ?? '',
    ]);
@endphp

<span {{ $classes }}>
    @if($dot)
        {{-- Screenreader Text für Dots --}}
        <span class="visually-hidden">{{ $slot->isEmpty() ? 'Notification' : $slot }}</span>
    @else
        {{ $slot }}
    @endif
</span>
