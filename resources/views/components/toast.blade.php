@props([
    'position' => config('bootstrap-ui.toast.position', 'top-end'),
])

@php
    $positionClasses = [
        'top-start' => 'top-0 start-0',
        'top-center' => 'top-0 start-50 translate-middle-x',
        'top-end' => 'top-0 end-0',
        'middle-center' => 'top-50 start-50 translate-middle',
        'bottom-start' => 'bottom-0 start-0',
        'bottom-center' => 'bottom-0 start-50 translate-middle-x',
        'bottom-end' => 'bottom-0 end-0',
    ];
    $posClass = $positionClasses[$position] ?? 'top-0 end-0';

    $configDuration = config('bootstrap-ui.toast.duration', 5000);
    $animate = config('bootstrap-ui.toast.animate', true);

    // Animation basierend auf Position
    $animationMap = [
        'top-start' => 'slideLeft',      // von links
        'top-center' => 'slideDown',     // von oben
        'top-end' => 'slideRight',       // von rechts
        'middle-center' => 'fade',       // fade in/out
        'bottom-start' => 'slideLeft',   // von links
        'bottom-center' => 'slideUp',    // von unten
        'bottom-end' => 'slideRight',    // von rechts
    ];
    $animationType = $animationMap[$position] ?? 'slideRight';

    $initialToasts = [];
    if (session()->has('bs-toasts')) {
        foreach (session('bs-toasts') as $item) {
            $item['icon'] = match($item['variant']) {
                'success' => 'bi bi-check-circle-fill text-success',
                'danger' => 'bi bi-x-circle-fill text-danger',
                'warning' => 'bi bi-exclamation-circle-fill text-warning',
                'info' => 'bi bi-info-circle-fill text-info',
                default => 'bi bi-bell-fill',
            };
            $initialToasts[] = $item;
        }
    }
@endphp

<div
    wire:ignore
    class="toast-container position-fixed p-3 {{ $posClass }}"
    style="z-index: 1060;"
    x-data="{
        toasts: [],
        duration: @js($configDuration),
        animate: @js($animate),
        animationType: @js($animationType),

        init() {
            this.toasts = @json($initialToasts);

            if (this.duration > 0) {
                this.toasts.forEach(toast => {
                    setTimeout(() => {
                        this.remove(toast.id);
                    }, this.duration);
                });
            }
        },

        add(detail) {
            const id = 'toast-' + Date.now() + '-' + Math.floor(Math.random() * 10000);

            let icon = 'bi bi-bell-fill';
            if(detail.variant === 'success') icon = 'bi bi-check-circle-fill text-success';
            if(detail.variant === 'danger') icon = 'bi bi-x-circle-fill text-danger';
            if(detail.variant === 'warning') icon = 'bi bi-exclamation-circle-fill text-warning';
            if(detail.variant === 'info') icon = 'bi bi-info-circle-fill text-info';

            const time = detail.timestamp || new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

            this.toasts.push({
                id: id,
                variant: detail.variant,
                title: detail.title,
                message: detail.message,
                timestamp: time,
                icon: icon
            });

            if (this.duration > 0) {
                setTimeout(() => {
                    this.remove(id);
                }, this.duration);
            }
        },

        remove(id) {
            const element = document.querySelector('[data-toast-id=' + id + ']');
            if (element && this.animate) {
                element.classList.remove('toast-enter-' + this.animationType);
                element.classList.add('toast-hiding-' + this.animationType);
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            } else {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
        },

        getEnterClass() {
            return this.animate ? 'toast-enter-' + this.animationType : 'toast-no-animation';
        }
    }"
    @bs-toast-message.window="add($event.detail.toast)"
>
<template x-for="(t, index) in toasts">
    <div
            :key="index"
            :data-toast-id="t.id"
            class="toast show mb-2"
            :class="getEnterClass()"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
    >
        <div class="toast-header">
            <i :class="t.icon" class="me-2 fs-5"></i>
            <strong class="me-auto" x-text="t.title"></strong>
            <small class="text-muted" x-text="t.timestamp"></small>
            <button type="button" class="btn-close" @click="remove(t.id)" aria-label="Close"></button>
        </div>
        <div class="toast-body" x-text="t.message"></div>
    </div>
</template>
</div>
