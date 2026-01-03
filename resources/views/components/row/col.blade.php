@props([
    'size' => null,     // Standardgröße (xs) oder feste Breite
    'sm' => null,       // ≥ 576px
    'md' => null,       // ≥ 768px
    'lg' => null,       // ≥ 992px
    'xl' => null,       // ≥ 1200px
    'xxl' => null,      // ≥ 1400px
    'order' => null,    // 0-5, first, last
    'vAlign' => null,   // Vertikale Ausrichtung: 'start', 'center', 'end'
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $vAlign = $attributes->pluck('align:vertical', $vAlign);


    $hasSize = $size || $sm || $md || $lg || $xl || $xxl;

    if (!$hasSize) {
        $field->addClass('col');
    } else {
        $field->addClassWhen($size, 'col-' . $size)
              ->addClassWhen($sm, 'col-sm-' . $sm)
              ->addClassWhen($md, 'col-md-' . $md)
              ->addClassWhen($lg, 'col-lg-' . $lg)
              ->addClassWhen($xl, 'col-xl-' . $xl)
              ->addClassWhen($xxl, 'col-xxl-' . $xxl);
    }

    // Erlaubt: order="1", order="first", order="last"
    // Align Self (Vertikal für DIESE Column)
    $field->addClassWhen($order, "order-$order")
          ->addClassWhen($vAlign, 'align-self-' . $vAlign);
@endphp

<div {{ $attributes->class($field->getClasses()) }}>
    {{ $slot }}
</div>
