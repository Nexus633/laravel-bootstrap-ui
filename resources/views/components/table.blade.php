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
    $classes = ['table', 'mb-0'];
    if ($striped)    $classes[] = 'table-striped';
    if ($hover)      $classes[] = 'table-hover';
    if ($bordered)   $classes[] = 'table-bordered';
    if ($borderless) $classes[] = 'table-borderless';
    if ($small)      $classes[] = 'table-sm';
    if ($variant)    $classes[] = 'table-' . $variant;
    if ($captionTop) $classes[] = 'caption-top';
@endphp

@if($responsive)
    {{-- WICHTIG: Der Container braucht overflow-x: auto --}}
    <div class="table-responsive">
@endif

    <table
        x-data="bsTableSticky"
        {{ $attributes->class($classes) }}
    >
        {{ $slot }}
    </table>

@if($responsive)
    </div>
@endif
