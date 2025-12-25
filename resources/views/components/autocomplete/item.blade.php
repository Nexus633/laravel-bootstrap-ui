@props([
    'value' => null,
    'label' => null,
])

@php
    $displayLabel = $label ?? $value;
@endphp

<li data-autocomplete-item data-value="{{ $value }}" data-label="{{ $displayLabel }}">
    <x-bs::link
        href="#"
        class="dropdown-item"
        x-bind:class="{ 'active': isHighlighted($el) }"
        @mouseenter="highlightByElement($el)"
        @mousedown.prevent="selectFromStatic($el)"
    >
        {{ $slot->isEmpty() ? $displayLabel : $slot }}
    </x-bs::link>
</li>
