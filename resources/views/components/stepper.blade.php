@props([
    'step' => 1, // Startwert (oder Livewire Binding)
])

<div
    x-data="{
        step: @entangle($attributes->wire('model')).live ?? {{ $step }},
        total: 0,

        init() {
            // Wir zählen die Steps im Header, um das Total zu wissen
            // $nextTick wartet, bis der DOM fertig ist
            this.$nextTick(() => {
                this.total = this.$refs.head.children.length;
            });
        },

        next() {
            if (this.step < this.total) this.step++;
        },

        prev() {
            if (this.step > 1) this.step--;
        },

        goTo(i) {
            this.step = i;
        },

        isLast() {
            return this.step === this.total;
        }
    }"
    {{ $attributes->class(['d-flex flex-column gap-4']) }}
>
    {{ $slot }}
</div>
