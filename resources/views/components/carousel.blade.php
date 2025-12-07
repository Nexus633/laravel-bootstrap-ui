@props([
    'id' => null,
    'controls' => false,
    'indicators' => false, // Standard: AUS. Muss explizit aktiviert werden.
    'fade' => false,
    'touch' => true,
    'ride' => null,
    'interval' => 5000,
    'pause' => 'hover',
])

@php
    $id = $id ?? 'carousel-' . uniqid();

    $classes = [
        'carousel',
        'slide',
        'carousel-fade' => $fade,
    ];

    $dataAttributes = [
        'id' => $id,
        'data-bs-ride' => $ride,
        'data-bs-interval' => $interval,
        'data-bs-touch' => $touch ? 'true' : 'false',
        'data-bs-pause' => $pause,
    ];
@endphp

<div
    {{ $attributes->merge($dataAttributes)->class($classes) }}
    x-data="{
        total: 0,
        current: 0,
        init() {
            // Wir zählen IMMER, damit die Logik bereit ist, falls indicators=true
            this.total = this.$el.querySelectorAll('.carousel-item').length;

            const carouselEl = document.getElementById('{{ $id }}');
            if(carouselEl) {
                carouselEl.addEventListener('slide.bs.carousel', (event) => {
                    this.current = event.to;
                });
            }
        }
    }"
>
    {{--
        INDICATORS (Opt-In)
        Nur rendern, wenn der Nutzer 'indicators' angegeben hat.
        Die Anzahl der Punkte wird trotzdem automatisch berechnet (Alpine).
    --}}
    @if($indicators)
        <div class="carousel-indicators" x-show="total > 1" style="display: none;">
            <template x-for="i in total">
                <button
                        type="button"
                        data-bs-target="#{{ $id }}"
                        :data-bs-slide-to="i - 1"
                        :class="current === (i - 1) ? 'active' : ''"
                        :aria-current="current === (i - 1) ? 'true' : 'false'"
                        :aria-label="'Slide ' + i"
                ></button>
            </template>
        </div>
    @endif

    <div class="carousel-inner">
        {{ $slot }}
    </div>

    @if($controls)
        <button class="carousel-control-prev" type="button" data-bs-target="#{{ $id }}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#{{ $id }}" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    @endif
</div>
