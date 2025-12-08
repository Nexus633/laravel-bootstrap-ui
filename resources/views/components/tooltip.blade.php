@props([
    'text',
    'placement' => config('bootstrap-ui.tooltip.position', 'top'),
    'customClass' => config('bootstrap-ui.tooltip.custom_class', ''),
])

@php
    // Prüfen, ob placement auch das entsprechende flag hat.
    $acceptPlacement = ['top', 'bottom', 'left', 'right'];
    if(!in_array($placement, $acceptPlacement)){
        $placement = config('bootstrap-ui.tooltip.position', 'top');
    }

    // Wir geben die Möglichkeit, einfacher das Placement anzugeben.
    $top = $attributes->get('top');
    $bottom = $attributes->get('bottom');
    $left = $attributes->get('left');
    $right = $attributes->get('right');

    // Wir entfernen die custom attribute wieder aus dem Case
    $attributes = $attributes->except(['top', 'bottom', 'left', 'right']);

    if($top) $placement = 'top';
    if($bottom) $placement = 'bottom';
    if($left) $placement = 'left';
    if($right) $placement = 'right';

@endphp
{{-- 
    Wir nutzen ein <span> als Wrapper. 
    x-init erstellt die Bootstrap Instanz. 
    $cleanup sorgt dafür, dass keine Speicherlecks entstehen, wenn Livewire das Element entfernt.
--}}
<span
    x-tooltip="'{{ $text }}'"
    {{-- Diese Attribute liest unser JS jetzt explizit aus --}}
    data-bs-placement="{{ $placement }}"

    {{-- Nur rendern, wenn eine Klasse existiert --}}
    @if($customClass) data-bs-custom-class="{{ $customClass }}" @endif
    {{ $attributes->merge(['class' => 'd-inline-block cursor-pointer']) }}
>
    {{ $slot }}
</span>
