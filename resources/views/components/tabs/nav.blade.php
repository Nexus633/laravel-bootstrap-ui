@props([
    'type' => 'tabs',
    'fill' => false,
    'justified' => false,
])
@aware([
    'vertical' => false,
])
@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $tabs = $attributes->pluck('tabs');
    $pills = $attributes->pluck('pills');
    $underline = $attributes->pluck('underline');

    if ($tabs)      $type = 'tabs';
    elseif ($pills) $type = 'pills';
    elseif ($underline) $type = 'underline';

    $ui = BootstrapUi::make()->addClass('nav');
    $ui->addClassWhen($type, 'nav-'.$type);
    $ui->addClassWhen($fill, 'nav-fill');
    $ui->addClassWhen($justified, 'nav-justified');
    $ui->addClassWhen($vertical, ['flex-column', 'me-3', 'border-bottom-0', 'nav-pills']);
@endphp

<ul {{ $attributes->class($ui->getClasses()) }}
    role="tablist"
    aria-orientation="{{ $vertical ? 'vertical' : 'horizontal' }}"
>
    {{ $slot }}
</ul>
