@props([
    'scroll' => false, // Für Scrollspy in der Nav
    'height' => null,  // Max-Height für Scroll
    'align' => null,   // 'start', 'center', 'end'
    'start' => false,
    'center' => false,
    'end' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();

    $start = $attributes->pluck('start', $start);
    $center = $attributes->pluck('center', $center);
    $end = $attributes->pluck('end', $end);

    if($start) $align = 'start';
    if($center) $align = 'center';
    if($end) $align = 'end';


    $field->addClass('navbar-nav')
          ->addClassWhen($scroll, 'navbar-nav-scroll')
          ->addClassWhen($align, match ($align) {
              'start'  => 'me-auto', // "Margin End Auto" -> Drückt alles Folgende nach rechts
              'end'    => 'ms-auto', // "Margin Start Auto" -> Drückt sich selbst nach rechts
              'center' => 'mx-auto', // "Margin X Auto" -> Zentriert sich (wenn Platz da ist)
              default  => null
          })
          ->addStyleWhen($scroll && $height, '--bs-scroll-height', $height);
@endphp

<ul {{ $attributes->class($field->getClasses())->merge(['style' => $field->getStyles()]) }}>
    {{ $slot }}
</ul>
