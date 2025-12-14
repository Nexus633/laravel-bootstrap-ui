@props([
    'id',
    'title' => null,
    'placement' => 'start',
    'backdrop' => true,
    'scroll' => false,
    'responsive' => null, // z.B. 'lg'
])

@php
    // Basis-Klasse ermitteln (offcanvas vs offcanvas-lg)
    $baseClass = $responsive ? 'offcanvas-' . $responsive : 'offcanvas';

    $classes = [
        $baseClass,
        'offcanvas-' . $placement,
    ];

    $dataAttrs = [];

    // Backdrop Logik
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
    {{--
       HEADER
       Logik: Wenn responsive='lg' gesetzt ist, fügen wir 'd-lg-none' hinzu.
       Dadurch verschwindet der Close-Button auf dem Desktop (wo er eh nicht geht).
    --}}
    <div class="offcanvas-header border-bottom {{ $responsive ? 'd-'.$responsive.'-none' : '' }}">
        <h5 class="offcanvas-title" id="{{ $labelId }}">
            {{ $title ?? $header ?? '' }}
        </h5>

        {{-- FIX: Wir geben explizit das Target mit an --}}
        <x-bs::button.close
            dismiss="offcanvas"
            target="#{{ $id }}"
        />
    </div>

    {{-- Body --}}
    <div class="offcanvas-body d-flex flex-column h-100 p-0 custom-scrollbar">
        {{ $slot }}
    </div>
</div>
