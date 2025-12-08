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
    // 1. Ausrichtung der Caption (Standard: Links)
    // Bootstrap Klassen: text-start, text-center, text-end
    $captionClass = 'text-start';

    // 2. Textgröße der Caption (Standard: 'sm')
    $captionSize = $attributes->get('caption:size') ?? $captionSize;


    // Boolean Flags prüfen
    $center = $attributes->get('center');
    $right = $attributes->get('right');
    // 'left' ist Standard, muss nicht explizit geprüft werden, 
    // aber wir holen es, um das Attribut zu säubern.
    $left = $attributes->get('left');

    if ($center) $captionClass = 'text-center';
    if ($right)  $captionClass = 'text-end';


    if($captionSize){
        $captionClass .= match ($captionSize){
            'xs' => ' fs-6',
            'sm' => ' fs-5',
            'md' => ' fs-4',
            'lg' => ' fs-3',
            'xl' => ' fs-2',
            'xxl' => ' fs-1',
        };
    }
    
    // Attribute säubern, damit 'center="center"' nicht im HTML landet
    $attributes = $attributes->except(['center', 'right', 'left', '$captionSize']);

    // 2. Bild-Klassen zusammenbauen
    $imgClasses = ['figure-img'];
    if ($fluid)   $imgClasses[] = 'img-fluid';
    if ($rounded) $imgClasses[] = 'rounded';
@endphp

<figure {{ $attributes->merge(['class' => 'figure']) }}>

    {{-- Das Bild --}}
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="{{ implode(' ', $imgClasses) }}"
        @if($lazy) loading="lazy" @endif
    >

    {{-- Die Caption (nur rendern, wenn Text vorhanden) --}}
    @if($caption)
        <figcaption class="figure-caption {{ $captionClass }}">
            {{ $caption }}
        </figcaption>
    @endif

    {{-- Slot: Falls man noch etwas in die Figure packen will --}}
    {{ $slot }}
</figure>
