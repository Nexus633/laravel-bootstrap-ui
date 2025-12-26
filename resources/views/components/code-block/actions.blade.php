@props([
    'uniqueId',
    'floating' => false,
    'copy' => true,
    'isJson' => false
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
        x-bind:title="isPretty ? 'Minimieren' : 'Formatieren'"
    >
        <span x-show="isPretty"><i class="bi bi-arrows-collapse"></i></span>
        <span x-show="!isPretty"><i class="bi bi-arrows-expand"></i></span>
    </x-bs::button>
</template>

{{-- Copy Button --}}
@if($copy)
    <x-bs::button.copy
        target="#raw-{{ $uniqueId }}"
        :variant="$btnVariant"
        size="sm"
        :class="$btnClass"
    />
@endif
