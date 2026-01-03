@props([
    'variant' => 'danger',
    'pill' => false,
    'dot' => false,
    'border' => false,
    'position' => null
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();

    $positions = [
        'top-start'    => 'top-0 start-0',
        'top-end'      => 'top-0 start-100',
        'bottom-start' => 'top-100 start-0',
        'bottom-end'   => 'top-100 start-100',
    ];

    $field->addClassWhen(!$dot, 'badge')
          ->addClassWhen($dot, 'p-2', 'rounded-circle')
          ->addClass(($dot ? 'bg-' : 'text-bg-') . $variant)
          ->addClassWhen($pill && !$dot, 'rounded-pill')
          ->addClassWhen($border, ['border', 'border-light'])
          ->addClassWhen($position, ['position-absolute', 'translate-middle', $positions[$position] ?? '']);
@endphp

<span {{ $attributes->class($field->getClasses()) }}>
    @if($dot)
        <span class="visually-hidden">{{ $slot->isEmpty() ? 'Notification' : $slot }}</span>
    @else
        {{ $slot }}
    @endif
</span>
