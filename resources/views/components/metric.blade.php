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
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

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

    // BootstrapUi Instanzen
    $labelUi = BootstrapUi::make();
    $iconHeaderUi = BootstrapUi::make();
    $valueUi = BootstrapUi::make();
    $trendTextUi = BootstrapUi::make();
    $subTextUi = BootstrapUi::make();

    // 3. Icon oben rechts stylen
    $iconHeaderUi->addClass('d-flex', 'align-items-center', 'justify-content-center', 'rounded-3');
    $iconHeaderUi->addStyle('width', '36px')->addStyle('height', '36px');
    $iconHeaderUi->addClassWhen($isCardColored, ['bg-white', 'bg-opacity-25', 'text-white'], ['bg-'.$variant.'-subtle', 'text-'.$variant]);

    // 4. Trend Farbe Logik (Das was du wolltest)
    $trendTextUi->addClass('d-flex', 'align-items-center', 'fw-semibold', 'me-2');
    if ($coloredTrend) {
        $trendTextUi->addClass('text-' . $trendColor, 'fw-bold');
    } else {
        $trendTextUi->addClassWhen($isCardColored, 'text-white', 'text-' . $trendColor);
    }

    // Label Klassen
    $labelUi->addClass('fw-medium', 'small', 'text-uppercase', 'tracking-wide');
    $labelUi->addClassWhen($isCardColored, ['text-white', 'opacity-75'], 'text-body-secondary');

    // Value Klassen
    $valueUi->addClass('card-title', 'fw-bold', 'mb-1');
    $valueUi->addClassWhen($isCardColored, 'text-white', 'text-body-emphasis');

    // Subtext Klassen
    $subTextUi->addClassWhen($isCardColored, ['text-white', 'opacity-75'], 'text-body-tertiary');
@endphp

<x-bs::card
    class="border-0 shadow-sm h-100"
    :variant="$bg"
    {{ $attributes }}
>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="{{ $labelUi->getClasses() }}">
            {{ $label }}
        </span>

        @if($icon)
            <div
                {{ $iconHeaderUi->getStyles() }}
            >
                <x-bs::icon :name="$icon" size="1.1rem" />
            </div>
        @endif
    </div>

    {{-- Value --}}
    <h3 class="{{ $valueUi->getClasses() }}">
        {{ $value }}
    </h3>

    {{-- Footer: Trend --}}
    @if($trend || $trendValue || !$slot->isEmpty())
        <div class="d-flex align-items-center mt-2 small">
            @if($trend)
                <span class="{{ $trendTextUi->getClasses() }}">
                    @if($trendIcon)
                        <x-bs::icon :name="$trendIcon" class="me-1" size="5"/>
                    @endif
                    {{ $trendValue }}
                </span>
            @endif

            <span class="{{ $subTextUi->getClasses() }}">
                {{ $slot }}
            </span>
        </div>
    @endif
</x-bs::card>
