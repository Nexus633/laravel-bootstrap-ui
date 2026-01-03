@props([
    'id',
    'title' => null,
    'placement' => 'start',
    'backdrop' => true,
    'scroll' => false,
    'responsive' => null, // z.B. 'lg'
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make()
          ->addClassWhen($responsive, 'offcanvas-' . $responsive, 'offcanvas')
          ->addClass('offcanvas-' . $placement)
          ->addDataWhen($backdrop === 'static', 'data-bs-backdrop', 'static')
          ->addDataWhen($backdrop === false, 'data-bs-backdrop', 'false')
          ->addDataWhen($scroll, 'data-bs-scroll', 'true');

    $labelId = $attributes->getOrCreateId('label-');
@endphp

<div
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $labelId }}"
    {{ $attributes->merge($field->getDataAttributes())->class($field->getClasses()) }}
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
