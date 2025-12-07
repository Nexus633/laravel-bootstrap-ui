@props([
    'id',                  // PFLICHT
    'title' => null,       // Titel im Header
    'placement' => 'start',// start, end, top, bottom
    'backdrop' => true,    // true, false, 'static'
    'scroll' => false,     // true = Body scrollt weiter
])

@php
    $classes = [
        'offcanvas',
        'offcanvas-' . $placement,
    ];

    // Data Attribute für Optionen bauen
    $dataAttrs = [];
    
    if ($backdrop === 'static') {
        $dataAttrs['data-bs-backdrop'] = 'static';
    } elseif ($backdrop === false) {
        $dataAttrs['data-bs-backdrop'] = 'false';
    }

    if ($scroll) {
        $dataAttrs['data-bs-scroll'] = 'true';
    }

    $labelId = $id . '-label';
@endphp

<div
        id="{{ $id }}"
        tabindex="-1"
        aria-labelledby="{{ $labelId }}"
        {{ $attributes->merge($dataAttrs)->class($classes) }}
>
    {{-- Header --}}
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="{{ $labelId }}">
            {{ $title ?? $header ?? '' }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    {{-- Body --}}
    <div class="offcanvas-body">
        {{ $slot }}
    </div>
</div>
