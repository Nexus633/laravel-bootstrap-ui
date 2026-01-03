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
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $verticalBetween = $attributes->pluck('vertical:between', $verticalBetween);

    $field->addClass('text-' . $variant)
          ->addClassWhen($vertical && $verticalBetween, 'mx-auto')
          ->addClassWhen($vertical, ['bs-divider-vertical', 'mx-2'])
          ->addClassWhen(!$vertical, 'bs-divider')
          ->addStyleWhen($width, 'width', $width)
          ->addStyleWhen(!$width, '--bs-divider-width', '100%');
@endphp

@if($vertical)
    {{-- Vertikaler Divider --}}
    <div {{ $attributes->class($field->getClasses())->merge(['style' => $field->getStyles()]) }}></div>
@else
    {{-- Horizontaler Divider --}}
    <div
        {{-- Breite als CSS Variable übergeben --}}
        {{ $attributes->class($field->getClasses())->merge(['style' => $field->getStyles()]) }}
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
