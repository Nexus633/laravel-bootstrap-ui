<div
    x-data="{ index: 0 }"
    x-init="
        // Wir ignorieren <template> tags oder scripts, zählen nur echte Divs im body
        // Array.from erstellt ein Array aus der HTMLCollection
        index = Array.from($el.parentElement.children).indexOf($el) + 1;
    "
    x-show="step === index"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    style="display: none;" {{-- Verhindert kurzes Aufblitzen beim Laden --}}
>
    {{ $slot }}
</div>
