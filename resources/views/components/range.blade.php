@props([
    'name',
    'label' => null,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'hint' => null,
])

@php
    $id = $attributes->get('id') ?? 'range-' . uniqid();
    $hasError = $name && $errors->has($name);
@endphp

{{-- 1. Wir nutzen den Wrapper für das Drumherum --}}
<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">

    {{-- 2. Der Range Input --}}
    <input
            type="range"
            name="{{ $name }}"
            id="{{ $id }}"
            min="{{ $min }}"
            max="{{ $max }}"
            step="{{ $step }}"
            {{-- Wichtig: Bootstrap Range nutzt 'form-range', nicht 'form-control' --}}
            {{ $attributes->class(['form-range', 'is-invalid' => $hasError]) }}
    />

</x-bs::input.wrapper>
