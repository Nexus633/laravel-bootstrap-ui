@props([
    'uniqueId',
    'floating' => false,
    'copy' => true,
    'isJson' => false,
    'iconExpand' => 'arrows-expand',
    'iconCollapse' => 'arrows-collapse'
])

{{-- 
    Logik für Styles:
    - Floating: Buttons sehen aus wie Links (kein Border), damit sie im schwebenden Container gut wirken.
    - Normal (Header): Dezent grau und rahmenlos oder outlined.
--}}
@php
    $btnVariant = $floating ? 'link' : 'outline-secondary';
    $btnClass = $floating 
        ? 'text-decoration-none text-body p-0 px-1' 
        : 'border-0 text-body-secondary p-0 px-1';
@endphp

{{-- JSON Toggle Button (nur sichtbar wenn Alpine isJson true ist) --}}
<template x-if="isJson">
    <x-bs::button
        :variant="$btnVariant"
        size="sm"
        :class="$btnClass"
        @click="toggleJson()"
        x-bind:title="isPretty ? '{{ __('bs::bootstrap-ui.code-block.minimize') }}' : '{{ __('bs::bootstrap-ui.code-block.formatted') }}'"
    >
        <x-bs::icon x-show="isPretty" :name="$iconCollapse" />
        <x-bs::icon x-show="!isPretty" :name="$iconExpand" />
    </x-bs::button>
</template>

{{-- Copy Button --}}
@if($copy)
    <x-bs::button.copy
        target="#raw-{{ $uniqueId }}"
        :variant="$btnVariant"
        size="sm"
        :class="$btnClass"
        :title="__('bs::bootstrap-ui.code-block.copy')"
    />
@endif
