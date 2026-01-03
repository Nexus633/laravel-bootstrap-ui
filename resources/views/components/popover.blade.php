@props([
    'title' => null,
    'content',
    'placement' => config('bootstrap-ui.popover.position', 'right'),
    'customClass' => config('bootstrap-ui.popover.custom_class', ''),
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    // Placement logic
    $acceptPlacement = ['top', 'bottom', 'left', 'right'];
    if(!in_array($placement, $acceptPlacement)){
        $placement = config('bootstrap-ui.popover.position', 'right');
    }

    $top = $attributes->pluck('top');
    $bottom = $attributes->pluck('bottom');
    $left = $attributes->pluck('left');
    $right = $attributes->pluck('right');

    if($top) $placement = 'top';
    if($bottom) $placement = 'bottom';
    if($left) $placement = 'left';
    if($right) $placement = 'right';

    $disabled = $attributes->get('disabled');

    // Build attributes using the helper
    $field->addData('data-bs-container', 'body')
          ->addData('data-bs-placement', $placement)
          ->addData('data-bs-content', $content)
          ->addData('role', 'button')
          ->addData('tabindex', '0')
          ->addDataWhen($title, 'data-bs-title', $title)
          ->addDataWhen($customClass, 'data-bs-custom-class', $customClass)
          ->addDataWhen($disabled, 'data-bs-trigger', 'hover focus')
          ->addClass('d-inline-block', 'cursor-pointer');
@endphp

<span
    x-bs-popover
    {{ $attributes->merge($field->getDataAttributes())->class($field->getClasses()) }}
>
    {{ $slot }}
</span>
