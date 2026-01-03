@props([
    'text',
    'placement' => config('bootstrap-ui.tooltip.position', 'top'),
    'customClass' => config('bootstrap-ui.tooltip.custom_class', ''),
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();

    // Prüfen, ob placement auch das entsprechende flag hat.
    $acceptPlacement = ['top', 'bottom', 'left', 'right'];
    if(!in_array($placement, $acceptPlacement)){
        $placement = config('bootstrap-ui.tooltip.position', 'top');
    }

    // Wir geben die Möglichkeit, einfacher das Placement anzugeben.
    $top = $attributes->pluck('top');
    $bottom = $attributes->pluck('bottom');
    $left = $attributes->pluck('left');
    $right = $attributes->pluck('right');

    if($top) $placement = 'top';
    if($bottom) $placement = 'bottom';
    if($left) $placement = 'left';
    if($right) $placement = 'right';

    $field->addClass('d-inline-block', 'cursor-pointer')
          ->addData('data-bs-placement', $placement)
          ->addDataWhen($customClass, 'data-bs-custom-class', $customClass);
@endphp
{{-- 
    Wir nutzen ein <span> als Wrapper. 
    x-init erstellt die Bootstrap Instanz. 
    $cleanup sorgt dafür, dass keine Speicherlecks entstehen, wenn Livewire das Element entfernt.
--}}
<span
    x-bs-tooltip="'{{ $text }}'"
    {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}
>
    {{ $slot }}
</span>
