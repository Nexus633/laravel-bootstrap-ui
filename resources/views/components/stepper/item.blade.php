@props([
    'label',
    'icon' => null
])

<div
    x-data="{ index: 0 }"
    x-init="
        // MAGIE: Finde heraus, der wievielte ich bin
        index = Array.from($el.parentElement.children).indexOf($el) + 1;
    "
    class="d-flex flex-column align-items-center cursor-pointer"
    style="min-width: 80px;"
    @click="goTo(index)" {{-- Klickbar machen --}}
>
    {{-- KREIS --}}
    <div
        class="d-flex align-items-center justify-content-center rounded-circle border border-2 transition-all"
        style="width: 40px; height: 40px;"
        :class="{
            'bg-success text-white border-success': step > index,   // Erledigt
            'bg-primary text-white border-primary shadow': step === index, // Aktiv
            'bg-body text-body-tertiary border-body-secondary': step < index // Offen
        }"
    >
        {{-- Logik: Wenn erledigt -> Haken. Sonst Icon oder Nummer --}}
        <template x-if="step > index">
            <x-bs::icon name="check-lg" size="1.2rem" />
        </template>

        <template x-if="step <= index">
            <span>
                @if($icon)
                    <x-bs::icon :name="$icon" size="1rem" />
                @else
                    <span class="fw-bold" x-text="index"></span>
                @endif
            </span>
        </template>
    </div>

    {{-- LABEL --}}
    <div
        class="mt-2 small text-center px-1 bg-body rounded"
        :class="step === index ? 'text-primary fw-bold' : (step > index ? 'text-success fw-medium' : 'text-body-secondary')"
    >
        {{ $label }}
    </div>
</div>
