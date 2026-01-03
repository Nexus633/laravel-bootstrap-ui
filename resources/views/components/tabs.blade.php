@props([
    'id' => null,
    'vertical' => false,
    'active' => '',
    'type' => 'tabs',
    'variant' => null,
])

@php
    $id = $attributes->getOrCreateId('tabs-');
@endphp

<div x-data="{
        activeTab: @if($attributes->wire('model')->value()) @entangle($attributes->wire('model')) @else '{{ $active }}' @endif,
        parentId: '{{ $id }}',
        isVertical: {{ $vertical ? 'true' : 'false' }},
        tabType: '{{ $type }}',
        tabVariant: '{{ $variant }}'
     }"
     :class="{ 'd-flex align-items-start': isVertical }"
     {{ $attributes->except(['wire:model', 'vertical', 'active', 'type', 'variant']) }}
>
    {{ $slot }}
</div>
