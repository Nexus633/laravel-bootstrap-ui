@php
    use Nexus633\BootstrapUi\Facades\Icon;

    $autoDismiss = config('bootstrap-ui.flash.auto_dismiss', 0);
    $animate = config('bootstrap-ui.flash.animate', true);

    // WICHTIG: Wir berechnen die Standard-Icons einmalig via Service
    // und übergeben sie an JS. So ist JS unabhängig von 'bi-' Strings.
    $defaultIcons = [
        'success' => Icon::toClass('check-circle-fill'),
        'danger'  => Icon::toClass('exclamation-triangle-fill'),
        'warning' => Icon::toClass('exclamation-circle-fill'),
        'info'    => Icon::toClass('info-circle-fill'),
        'default' => Icon::toClass('info-circle'),
    ];

    $jsConfig = [
        'initial' => [],
        'autoDismiss' => $autoDismiss,
        'animate' => $animate,
        'enterClass' => config('bootstrap-ui.flash.animate_class.enter', 'alert-enter'),
        'leaveClass' => config('bootstrap-ui.flash.animate_class.leave', 'alert-hiding'),
        'icons' => $defaultIcons, // <--- HIER NEU
    ];

    // --- Session Logic ---
    if (session()->has('bs-flash')) {
        foreach (session('bs-flash') as $f) {
            $f['id'] = $f['id'] ?? 'session-' . uniqid();

            // Icon via Service generieren
            $f['icon'] = Icon::toClass(match($f['variant']) {
                'success' => 'check-circle-fill',
                'danger'  => 'exclamation-triangle-fill',
                'warning' => 'exclamation-circle-fill',
                'info'    => 'info-circle-fill',
                default   => 'info-circle',
            });

            $jsConfig['initial'][] = $f;
        }
    }

    // Legacy support
    foreach (['success', 'danger', 'warning', 'info'] as $type) {
        if (session()->has($type)) {
            $variant = $type == 'error' ? 'danger' : $type;

            // Icon via Service generieren
            $iconClass = Icon::toClass(match($type) {
                'success' => 'check-circle-fill',
                'danger', 'error' => 'exclamation-triangle-fill',
                'warning' => 'exclamation-circle-fill',
                'info'    => 'info-circle-fill',
                default   => 'info-circle',
            });

            $jsConfig['initial'][] = [
                'id' => 'legacy-' . uniqid(),
                'variant' => $variant,
                'message' => session($type),
                'title' => null,
                'icon' => $iconClass
            ];
        }
    }
@endphp

<div class="w-100" wire:ignore>
    <div x-data="bsFlash(@js($jsConfig))">
        <template x-for="alert in alerts" :key="alert.id">
            <div
                :data-alert-id="alert.id"
                x-init="setupAutoDismiss($el, alert.id)"
                class="alert d-flex alert-dismissible shadow-sm mb-3"
                :class="['alert-' + alert.variant, classes.enter]"
                role="alert"
            >
                <x-bs::icon name="" size="4" x-bind:class="'flex-shrink-0 me-3 mt-1 ' + alert.icon" />

                <div class="flex-grow-1 text-break">
                    <template x-if="alert.title">
                        <x-bs::text h5 bold size="6" class="alert-heading mb-1" x-text="alert.title" />
                    </template>
                    <x-bs::text div x-text="alert.message" />
                </div>

                <x-bs::button.close @click="handleRemove($el.closest('.alert'), alert.id)" />
            </div>
        </template>
    </div>
</div>
