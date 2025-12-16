@props([
    'text' => null,
    'target' => null,
    'duration' => 2000,
    'icon' => 'clipboard',
    'successIcon' => 'check',
    'label' => null,
    'successLabel' => null,
    'variant' => 'primary',
    'successVariant' => 'success',
    'size' => null,
    'outline' => false,
])

@php
    $successIcon = $attributes->get('icon:success') ?? $successIcon;
    $successLabel = $attributes->get('label:success') ?? $successLabel;
    $successVariant = $attributes->get('variant:success') ?? $successVariant;

    $attributes = $attributes->except(['icon:success', 'label:success', 'variant:success']);


    $hasLabel = !empty($label) || !empty($successLabel);

    // Berechne die CSS-Klassennamen für die beiden Zustände
    $baseClass = $outline ? 'btn-outline-'.$variant : 'btn-'.$variant;
    $doneClass = $outline ? 'btn-outline-'.$successVariant : 'btn-'.$successVariant;
@endphp

{{-- Globaler Style Fix für x-cloak --}}
<style>
    [x-cloak] { display: none !important; }
</style>

<div
    x-data="bsCopyBtn({
        text: @js($text),
        target: @js($target),
        duration: {{ (int)$duration }}
    })"
    class="d-inline-block"
>
    {{--
        HIER IST DER FIX:
        1. variant="custom-override": Verhindert, dass der Button seine eigene Farbe setzt.
        2. class="{{ $baseClass }}": Setzt die Start-Farbe (PHP-seitig), damit der Button SOFORT sichtbar ist.
        3. x-bind:class: Alpine tauscht die Klassen dynamisch aus.
    --}}
    <x-bs::button
        variant="custom-override"
        :size="$size"
        @click="copyToClipboard()"
        class="{{ $baseClass }}"
        x-bind:class="{
            '{{ $baseClass }}': !copied,
            '{{ $doneClass }}': copied
        }"
        {{ $attributes }}
    >
        <div class="d-flex align-items-center justify-content-center">

            {{-- ICONS --}}
            <div class="d-flex">
                <span x-show="!copied">
                    <x-bs::icon :name="$icon" class="{{ $hasLabel ? 'me-1' : '' }}"/>
                </span>
                <span x-show="copied" x-cloak>
                    <x-bs::icon :name="$successIcon" class="{{ $hasLabel ? 'me-1' : '' }}"/>
                </span>
            </div>

            {{-- LABELS --}}
            @if($hasLabel)
                <div>
                    @if($label)
                        <span x-show="!copied">{{ $label }}</span>
                    @endif

                    @if($successLabel)
                        <span x-show="copied" x-cloak>{{ $successLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
    </x-bs::button>
</div>
