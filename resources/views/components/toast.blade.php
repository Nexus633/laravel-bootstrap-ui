@props([
    'position' => config('bootstrap-ui.toast.position', 'top-end'),
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $field = BootstrapUi::make();

    // CSS Klassen für Positionierung
    $positionClasses = [
        'top-start'     => 'top-0 start-0',
        'top-center'    => 'top-0 start-50 translate-middle-x',
        'top-end'       => 'top-0 end-0',
        'middle-center' => 'top-50 start-50 translate-middle',
        'bottom-start'  => 'bottom-0 start-0',
        'bottom-center' => 'bottom-0 start-50 translate-middle-x',
        'bottom-end'    => 'bottom-0 end-0',
    ];

    $field->addClass($positionClasses[$position] ?? 'top-0 end-0')
          ->addClass('toast-container', 'position-fixed', 'p-3')
          ->addStyle('z-index', '1060');

    // Konfiguration
    $configDuration = config('bootstrap-ui.toast.duration', 5000);
    $animate = config('bootstrap-ui.toast.animate', true);

    // Animations-Typ bestimmen
    $animationMap = [
        'top-start'     => 'slideLeft',
        'top-center'    => 'slideDown',
        'top-end'       => 'slideRight',
        'middle-center' => 'fade',
        'bottom-start'  => 'slideLeft',
        'bottom-center' => 'slideUp',
        'bottom-end'    => 'slideRight',
    ];
    $animationType = $animationMap[$position] ?? 'slideRight';

    // Initiale Toasts aus Session holen
    $initialToasts = [];
    if (session()->has('bs-toasts')) {
        foreach (session('bs-toasts') as $item) {
            $item['id'] = 'session-' . uniqid(); // ID sicherstellen
            $item['icon'] = Icon::toClass(match($item['variant']) {
                'success' => 'check-circle-fill text-success',
                'danger'  => 'x-circle-fill text-danger',
                'warning' => 'exclamation-circle-fill text-warning',
                'info'    => 'info-circle-fill text-info',
                default   => 'bell-fill',
            });
            // Timestamp Fallback für Session Toasts
            if (!isset($item['timestamp'])) {
                $item['timestamp'] = now()->format('H:i');
            }
            $initialToasts[] = $item;
        }
    }

    // JS Config Array
    $jsConfig = [
        'initial'       => $initialToasts,
        'duration'      => $configDuration,
        'animate'       => $animate,
        'animationType' => $animationType
    ];
@endphp

<div
    wire:ignore
    {{ $attributes->class($field->getClasses())->merge(['style' => $field->getStyles()]) }}
    x-data="bsToastStack(@js($jsConfig))"
>
    <template x-for="t in toasts" :key="t.id">
        <div
            :data-toast-id="t.id"
            class="toast show mb-2"
            :class="getEnterClass()"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
        >
            <div class="toast-header">
                {{-- Icon via Dynamic Class binding --}}
                <x-bs::icon name="" size="5" x-bind:class="'me-2 ' + t.icon" />

                <x-bs::text span bold class="me-auto" x-text="t.title" />
                <x-bs::text small class="text-muted" x-text="t.timestamp" />

                {{-- Close Button --}}
                <x-bs::button.close @click="remove(t.id)" />
            </div>
            <x-bs::text div class="toast-body" x-text="t.message" />
        </div>
    </template>
</div>
