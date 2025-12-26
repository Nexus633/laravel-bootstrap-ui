@props([
    'title',
    'text' => null,
    'icon' => null,
    /*
     * Icon Größe:
     * Zahl (1-6) -> fs-{n} Klasse (Bootstrap Standard)
     * String (z.B. '4rem', '60px') -> style="font-size: ..."
     */
    'iconSize' => '4rem'
])

@php
    // Attribut gewinnt vor Prop (ermöglicht <x-bs::empty-state icon:size="2" ... />)
    $iconSize = $attributes->get('icon:size', $iconSize);
    $attributes = $attributes->except('icon:size');
@endphp

<div {{ $attributes->merge(['class' => 'text-center py-5']) }}>

    {{-- Icon --}}
    @if($icon)
        <div class="mb-3 text-secondary opacity-50">
            <x-bs::icon :name="$icon" :size="$iconSize" />
        </div>
    @endif

    {{-- Überschrift --}}
    <h4 class="fw-bold text-body-emphasis">
        {{ $title }}
    </h4>

    {{-- Text --}}
    @if($text)
        <p class="text-muted col-md-8 mx-auto mb-4">
            {{ $text }}
        </p>
    @endif

    {{-- Actions --}}
    @if(!$slot->isEmpty())
        <div class="d-flex justify-content-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
