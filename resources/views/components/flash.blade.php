@php
    $autoDismiss = config('bootstrap-ui.flash.auto_dismiss', 0);
    $animate = config('bootstrap-ui.flash.animate', true);
    $animate_enter_class = config('bootstrap-ui.flash.animate_class.enter', 'alert-enter');
    $animate_leave_class = config('bootstrap-ui.flash.animate_class.leave', 'alert-hiding');

    $initialFlashes = [];
    if (session()->has('bs-flash')) {
        foreach (session('bs-flash') as $f) {
            $f['id'] = $f['id'] ?? 'session-' . uniqid() . '-' . bin2hex(random_bytes(4));
            $f['icon'] = match($f['variant']) {
                'success' => 'bi bi-check-circle-fill',
                'danger' => 'bi bi-exclamation-triangle-fill',
                'warning' => 'bi bi-exclamation-circle-fill',
                'info' => 'bi bi-info-circle-fill',
                default => 'bi bi-info-circle',
            };
            $initialFlashes[] = $f;
        }
    }
    // Legacy support...
    foreach (['success', 'danger', 'warning', 'info'] as $type) {
        if (session()->has($type)) {
            $initialFlashes[] = [
                'id' => 'legacy-' . uniqid() . '-' . bin2hex(random_bytes(4)),
                'variant' => $type == 'error' ? 'danger' : $type,
                'message' => session($type),
                'title' => null,
                'icon' => match($type) {
                    'success' => 'bi bi-check-circle-fill',
                    'danger', 'error' => 'bi bi-exclamation-triangle-fill',
                    'warning' => 'bi bi-exclamation-circle-fill',
                    'info' => 'bi bi-info-circle-fill',
                    default => 'bi bi-info-circle',
                }
            ];
        }
    }
@endphp

<div class="w-100" wire:ignore>
    <div
            x-data="{
            alerts: [],
            autoDismiss: @js($autoDismiss),
            animate: @js($animate),
            animate_enter_class: @js($animate_enter_class),
            animate_leave_class: @js($animate_leave_class),


            init() {
                this.alerts = @json($initialFlashes);
            },

            add(detail) {
                if (this.alerts.some(a => a.message === detail.message && a.variant === detail.variant)) {
                    return;
                }

                const id = detail.id || ('live-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9));

                let icon = 'bi bi-info-circle';
                if(detail.variant === 'success') icon = 'bi bi-check-circle-fill';
                if(detail.variant === 'danger') icon = 'bi bi-exclamation-triangle-fill';
                if(detail.variant === 'warning') icon = 'bi bi-exclamation-circle-fill';
                if(detail.variant === 'info') icon = 'bi bi-info-circle-fill';

                this.alerts.push({
                    id: id,
                    variant: detail.variant,
                    message: detail.message,
                    title: detail.title,
                    icon: icon
                });
            },

            handleRemove(element, id) {
                if (this.animate) {
                    element.classList.add(this.animate_leave_class);
                    setTimeout(() => {
                        this.alerts = this.alerts.filter(a => a.id !== id);
                    }, 250);
                } else {
                    this.alerts = this.alerts.filter(a => a.id !== id);
                }
            },

            setupAutoDismiss(element, id) {
                if (this.autoDismiss > 0) {
                    setTimeout(() => {
                        this.handleRemove(element, id);
                    }, this.autoDismiss);
                }
            }
        }"
            @bs-flash-message.window="add($event.detail.flash)"
    >
        <template x-for="(alert, index) in alerts">
            <div
                :key="index"
                :data-alert-id="alert.id"
                x-init="setupAutoDismiss($el, alert.id)"
                class="alert d-flex alert-dismissible shadow-sm mb-3"
                :class="['alert-' + alert.variant, @js($animate) ? @js($animate_enter_class) : '']"
                role="alert"
            >
                <i :class="alert.icon" class="flex-shrink-0 me-3 fs-4 mt-1"></i>

                <div class="flex-grow-1 text-break">
                    <template x-if="alert.title">
                        <h5 class="alert-heading fs-6 fw-bold mb-1" x-text="alert.title"></h5>
                    </template>
                    <div x-text="alert.message"></div>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    @click="handleRemove($el.closest('.alert'), alert.id)"
                    aria-label="Close"
                ></button>
            </div>
        </template>
    </div>
</div>
