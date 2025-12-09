@props([
    'title' => null,     // Die Überschrift (fett)
    'content',           // Der eigentliche Text
    'placement' => config('bootstrap-ui.popover.position', 'right'),
    'customClass' => config('bootstrap-ui.popover.custom_class', ''),
])

@php
    // Deine Boolean-Flag Logik (Wiederverwendet)
    $acceptPlacement = ['top', 'bottom', 'left', 'right'];
    if(!in_array($placement, $acceptPlacement)){
        $placement = config('bootstrap-ui.popover.position', 'right');
    }

    $top = $attributes->get('top');
    $bottom = $attributes->get('bottom');
    $left = $attributes->get('left');
    $right = $attributes->get('right');

    $attributes = $attributes->except(['top', 'bottom', 'left', 'right']);

    if($top) $placement = 'top';
    if($bottom) $placement = 'bottom';
    if($left) $placement = 'left';
    if($right) $placement = 'right';

    $disabled = $attributes->get('disabled');
    $triggerByDisabled = '';


    if($disabled){
        $triggerByDisabled = 'data-bs-trigger="hover focus"';
    }
@endphp

<span
    {{-- Initialisierung via Alpine --}}
    x-bs-popover

    {{-- Bootstrap Data Attributes --}}
    data-bs-container="body"
    data-bs-placement="{{ $placement }}"
    data-bs-content="{{ $content }}"
    {!! $triggerByDisabled !!}
    {{-- Titel ist optional beim Popover --}}
    @if($title) data-bs-title="{{ $title }}" @endif

    {{-- Custom Class --}}
    @if($customClass) data-bs-custom-class="{{ $customClass }}" @endif

    {{--
        tabindex="0" ist PFLICHT für trigger="focus" bei nicht-interaktiven Elementen (span/div).
        role="button" ist gut für Barrierefreiheit.
    --}}
    tabindex="0"
    role="button"

    {{ $attributes->merge(['class' => 'd-inline-block cursor-pointer']) }}
>
    {{ $slot }}
</span>
