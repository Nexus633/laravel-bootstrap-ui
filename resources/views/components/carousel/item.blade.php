@props([
    'active' => false,      // Ist dies der Start-Slide?
    'interval' => null,     // Spezifische Zeit für DIESEN Slide
    'image' => null,        // Bild URL
    'alt' => '',            // Alt Text
    'captionTitle' => null, // Überschrift im Caption
    'captionText' => null,  // Text im Caption
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $captionTitle = $attributes->pluck('caption:title', $captionTitle);
    $captionText = $attributes->pluck('caption:text', $captionText);

    $field->addClass('carousel-item')
          ->addClassWhen($active, 'active')
          ->addDataWhen($interval, 'data-bs-interval', $interval);

@endphp

<div {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
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
