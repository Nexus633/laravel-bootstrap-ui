@props([
    'active' => false,      // Ist dies der Start-Slide?
    'interval' => null,     // Spezifische Zeit für DIESEN Slide
    'image' => null,        // Bild URL
    'alt' => '',            // Alt Text
    'captionTitle' => null, // Überschrift im Caption
    'captionText' => null,  // Text im Caption
])

@php

    $captionTitle = $attributes->get('caption:title', $captionTitle);
    $captionText = $attributes->get('caption:text', $captionText);
    $attributes = $attributes->except(['caption:title', 'caption:text']);
    $classes = [
        'carousel-item',
        'active' => $active,
    ];
@endphp

<div {{ $attributes->class($classes) }}
     @if($interval) data-bs-interval="{{ $interval }}" @endif>

    {{-- BILD (Optional) --}}
    @if($image)
        <img src="{{ $image }}" class="d-block w-100" alt="{{ $alt }}">
    @endif

    {{-- CUSTOM CONTENT (Slot) --}}
    {{ $slot }}

    {{-- CAPTION (Optional) --}}
    @if($captionTitle || $captionText)
        <div class="carousel-caption d-none d-md-block">
            @if($captionTitle)
                <h5>{{ $captionTitle }}</h5>
            @endif
            @if($captionText)
                <p>{{ $captionText }}</p>
            @endif
        </div>
    @endif
</div>
