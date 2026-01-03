@props([
    'src',                  // Bildquelle
    'alt' => '',            // Alt-Text (wichtig für SEO/Accessibility)
    'caption' => null,      // Die Bildunterschrift
    'captionSize' => null,  // Die Caption Text größe: 'xs', 'sm', 'md', 'lg', 'xl', 'xxl'
    'fluid' => true,        // Standard: img-fluid (responsive)
    'rounded' => true,      // Standard: abgerundete Ecken
    'lazy' => true,         // Standard: Native Lazy Loading
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();
    $img = BootstrapUi::make();

    $captionSize = $attributes->pluck('caption:size', $captionSize);
    $center = $attributes->pluck('center');
    $right = $attributes->pluck('right');
    $left = $attributes->pluck('left');

    $field->addClass('figure-caption')
          ->addClass('text-start')
          ->addClassWhen($center, 'text-center')
          ->addClassWhen($right, 'text-end')
          ->addClassWhen($captionSize, match ($captionSize){
            'xs' => ' fs-6',
            'sm' => ' fs-5',
            'md' => ' fs-4',
            'lg' => ' fs-3',
            'xl' => ' fs-2',
            'xxl' => ' fs-1',
            default => null
        });

    // 2. Bild-Klassen zusammenbauen
    $imgClasses = ['figure-img'];
    $img->addClass('figure-img')
         ->addClassWhen($fluid, 'img-fluid')
         ->addClassWhen($rounded, 'rounded');

@endphp

<figure {{ $attributes->merge(['class' => 'figure']) }}>

    {{-- Das Bild --}}
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="{{ $img->getClasses() }}"
        @if($lazy) loading="lazy" @endif
    >

    {{-- Die Caption (nur rendern, wenn Text vorhanden) --}}
    @if($caption)
        <figcaption class="{{ $field->getClasses() }}">
            {{ $caption }}
        </figcaption>
    @endif

    {{-- Slot: Falls man noch etwas in die Figure packen will --}}
    {{ $slot }}
</figure>
