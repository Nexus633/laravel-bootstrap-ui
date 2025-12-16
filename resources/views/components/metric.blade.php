@props([
    'label',
    'value',
    'icon' => null,
    'trend' => null,        // 'up', 'down', 'neutral'
    'trendValue' => null,
    'variant' => 'body', // Farbe für das Icon oben rechts (wenn Karte weiß ist)
    'bg' => null,           // Hintergrund der gesamten Karte
    'coloredTrend' => false // NEU: Erzwingt farbigen Trend auch auf farbigem Hintergrund
])

@php
    // 1. Semantische Trend-Farbe bestimmen (Unabhängig vom Rest)
    $trendColor = match ($trend) {
        'up' => 'success',
        'down' => 'danger',
        'neutral' => 'warning',
        default => 'body-secondary',
    };

    $trendIcon = match ($trend) {
        'up' => 'arrow-up-short',
        'down' => 'arrow-down-short',
        'neutral' => 'dash',
        default => null
    };

    // 2. Ist die Karte farbig?
    $isCardColored = !empty($bg);

    // 3. Icon oben rechts stylen
    // Farbiges BG -> Icon Weiß Transparent
    // Weißes BG -> Icon in Variant-Farbe (z.B. Blau)
    $iconClass = $isCardColored
        ? 'bg-white bg-opacity-25 text-white'
        : 'bg-'.$variant.'-subtle text-'.$variant;

    // 4. Trend Farbe Logik (Das was du wolltest)
    if ($coloredTrend) {
        // Option AN: Immer Grün/Rot, egal welcher Hintergrund
        // Wir fügen 'bg-white rounded px-1' hinzu (optional), falls der Kontrast auf Blau zu schlecht ist?
        // Nein, du wolltest nur die Farbe. Ich mache es zusätzlich FETT, damit man es auf Blau lesen kann.
        $trendTextClass = 'text-' . $trendColor . ' fw-bold';

        // Falls du willst, dass das Icon auch die Farbe hat, muss es hier rein.
        // Bootstrap Text-Klassen färben Icons automatisch mit.
    } else {
        // Option AUS (Standard): Weiß auf farbigem Grund, sonst Grün/Rot
        $trendTextClass = $isCardColored ? 'text-white' : 'text-' . $trendColor;
    }

    // Subtext (z.B. "vs Vormonat")
    $subTextClass = $isCardColored ? 'text-white opacity-75' : 'text-body-tertiary';
@endphp

<x-bs::card
    class="border-0 shadow-sm h-100"
    :variant="$bg"
    {{ $attributes }}
>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="{{ $isCardColored ? 'text-white opacity-75' : 'text-body-secondary' }} fw-medium small text-uppercase tracking-wide">
            {{ $label }}
        </span>

        @if($icon)
            <div
                class="d-flex align-items-center justify-content-center rounded-3 {{ $iconClass }}"
                style="width: 36px; height: 36px;"
            >
                <x-bs::icon :name="$icon" size="1.1rem" />
            </div>
        @endif
    </div>

    {{-- Value --}}
    <h3 class="card-title fw-bold mb-1 {{ $isCardColored ? 'text-white' : 'text-body-emphasis' }}">
        {{ $value }}
    </h3>

    {{-- Footer: Trend --}}
    @if($trend || $trendValue || !$slot->isEmpty())
        <div class="d-flex align-items-center mt-2 small">
            @if($trend)
                <span class="d-flex align-items-center fw-semibold me-2 {{ $trendTextClass }}">
                    @if($trendIcon)
                        <x-bs::icon :name="$trendIcon" class="me-1" size="5"/>
                    @endif
                    {{ $trendValue }}
                </span>
            @endif

            <span class="{{ $subTextClass }}">
                {{ $slot }}
            </span>
        </div>
    @endif
</x-bs::card>
