@props([
    'striped' => false,
    'hover' => false,
    'bordered' => false,
    'borderless' => false,
    'small' => false,
    'variant' => null,
    'captionTop' => false,
    'responsive' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    $field->addClass('table', 'mb-0')
          ->addClassWhen($striped, 'table-striped')
          ->addClassWhen($hover, 'table-hover')
          ->addClassWhen($bordered, 'table-bordered')
          ->addClassWhen($borderless, 'table-borderless')
          ->addClassWhen($small, 'table-sm')
          ->addClassWhen($variant, 'table-' . $variant)
          ->addClassWhen($captionTop, 'caption-top');
@endphp

@if($responsive)
    {{-- WICHTIG: Der Container braucht overflow-x: auto --}}
    <div class="table-responsive">
@endif

    <table
        x-data="bsTableSticky"
        {{ $attributes->class($field->getClasses()) }}
    >
        {{ $slot }}
    </table>

@if($responsive)
    </div>
@endif
