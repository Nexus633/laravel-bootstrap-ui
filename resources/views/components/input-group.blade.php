@props([
    'label' => null,
    'name' => null, // Optional: Feldname für Error-Anzeige unter der gesamten Gruppe
    'hint' => null,
    'id' => null,
    'size' => null,
])

@php
    $id = $attributes->getOrCreateId('group-');
@endphp

{{-- Wir nutzen denselben Wrapper, damit Labels & Errors konsistent aussehen --}}
<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">

    <x-bs::input.group :size="$size" {{ $attributes }}>
        {{ $slot }}
    </x-bs::input.group>

</x-bs::input.wrapper>
