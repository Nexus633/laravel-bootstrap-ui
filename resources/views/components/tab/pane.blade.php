@props([
    'id',
    'active' => false
])
@php
    $classes = [
        'tab-pane',
        'fade'
        ];
    if($active) $classes = array_merge($classes, ['show', 'active']);
@endphp
<div id="{{ $id }}" {{ $attributes->class($classes) }} role="tabpanel" tabindex="0">
    {{ $slot }}
</div>
