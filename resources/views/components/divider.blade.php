@props([
    'text' => null,
    'icon' => null,
    'align' => 'center', // 'start', 'center', 'end'
    'vertical' => false,
    'verticalBetween' => false,
    'variant' => 'body-tertiary',
    'width' => null,
])

@php

    $verticalBetween = $attributes->get('vertical:between', $verticalBetween);
    $attributes = $attributes->except(['vertical:between']);

    // 1. Text-Farbe via Bootstrap Klasse (z.B. text-danger)
    $classes = ['text-' . $variant];

    // 2. Wenn Breite gesetzt ist, zentrieren wir den Divider selbst (mx-auto)
    if ($vertical && $verticalBetween) {
        $classes[] = 'mx-auto';
    }
@endphp

@if($vertical)
    {{-- Vertikaler Divider --}}
    <div
            @if($width)
                style="width: {{ $width }}"
            @endif
            {{ $attributes->class(array_merge(['bs-divider-vertical', 'mx-2'], $classes)) }}
    ></div>
@else
    {{-- Horizontaler Divider --}}
    <div
        {{-- Breite als CSS Variable übergeben --}}
        style="--bs-divider-width: 100%"
        {{ $attributes->class(array_merge(['bs-divider'], $classes)) }}
    >

        {{-- Linie Links (Nur anzeigen, wenn nicht 'start' ausgerichtet) --}}
        @if($align !== 'start')
            <div class="bs-divider-line"></div>
        @endif

        {{-- Inhalt (Nur rendern, wenn Text oder Icon da ist) --}}
        @if(!empty($text) || !empty($icon) || $slot->isNotEmpty())
            <span class="bs-divider-content">
                @if($icon)
                    <x-bs::icon :name="$icon" />
                @endif

                @if(!empty($text) || $slot->isNotEmpty())
                    <span>{{ $text ?? $slot }}</span>
                @endif
            </span>
        @endif

        {{-- Linie Rechts (Nur anzeigen, wenn nicht 'end' ausgerichtet) --}}
        @if($align !== 'end')
            <div class="bs-divider-line"></div>
        @endif
    </div>
@endif
