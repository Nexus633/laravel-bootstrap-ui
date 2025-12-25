@props([
    'results' => [],
    'label' => null,
    'placeholder' => '',
    'searchModel' => null,
    'selectModel' => null,
    'selectField' => 'id',
    'displayField' => 'name',
    'minChars' => 2,
    'static' => false,
])

@php
    $id = $attributes->get('id') ?? 'auto_' . uniqid();
    $name = $attributes->get('name') ?? 'auto_' . uniqid();
    $hasResults = count($results) > 0;
    $hasSlot = !$slot->isEmpty();

    // Statischer Modus wenn Slot gefüllt ist
    $isStatic = $static || $hasSlot;

    $dynamicAttributes = [];

    // Logik: Entweder Livewire-Model ODER Alpine-Model
    if (!$isStatic && $searchModel) {
        $dynamicAttributes['wire:model.live.debounce.500ms'] = $searchModel;
    } else {
        $dynamicAttributes['x-model'] = 'searchTerm';
    }
@endphp

<div
    x-data="bsAutocomplete({
        searchModel: '{{ $searchModel }}',
        selectModel: '{{ $selectModel }}',
        selectField: '{{ $selectField }}',
        isStatic: {{ $isStatic ? 'true' : 'false' }},
        minChars: {{ $minChars }}
    })"
    @click.outside="closeDropdown()"
    @keydown.escape="closeDropdown()"
    class="position-relative"
>
    @if($label)
        <x-bs::text label for="{{ $id }}" class="form-label">{{ $label }}</x-bs::text>
    @endif

    <x-bs::input
        simple
        x-ref="input"
        id="{{ $id }}"
        name="{{ $name }}"
        wire:key="autocomplete-input-{{ $searchModel ?? $selectModel ?? uniqid() }}"
        class="form-control"
        placeholder="{{ $placeholder }}"
        :attributes="$attributes->merge($dynamicAttributes)"
        @focus="onFocus()"
        @input="onInputChange()"
        @keydown.arrow-down.prevent="onArrowDown()"
        @keydown.arrow-up.prevent="onArrowUp()"
        @keydown.enter.prevent="onEnter()"
    />


    {{-- Dynamische Results (Livewire) --}}
    @if(!$isStatic)
        <div
            x-show="isOpen"
            x-transition
            class="dropdown-menu show mt-1 shadow-sm overflow-auto"
            style="position: absolute; z-index: 9999; width: 100%; top: 100%; left: 0; max-height: 200px;"
            x-ref="livewireDropdown"
            wire:ignore.self
        >
            @if($hasResults)
                <ul class="list-unstyled mb-0" x-ref="livewireResults">
                    @foreach($results as $index => $result)
                        @php
                            $selectValue = data_get($result, $selectField);
                            $displayValue = data_get($result, $displayField);
                        @endphp

                        <li>
                            <x-bs::link
                                href="#"
                                class="dropdown-item"
                                x-bind:class="{ 'active': highlightIndex === {{ $index }} }"
                                @mouseenter="highlightIndex = {{ $index }}"
                                data-index="{{ $index }}"
                                data-select="{{ $selectValue }}"
                                data-display="{{ $displayValue }}"
                                @mousedown.prevent="selectItem($el)"
                            >
                                {{ $displayValue }}
                            </x-bs::link>
                        </li>
                    @endforeach
                </ul>
            @else
                <div x-ref="livewireResults" style="display: none;"></div>
            @endif
        </div>
    @endif

    {{-- Statische Items (Slot) --}}
    @if($isStatic)
        <div
            x-show="isOpen && filteredCount > 0"
            x-transition
            class="dropdown-menu show mt-1 shadow-sm overflow-auto"
            style="position: absolute; z-index: 9999; width: 100%; top: 100%; left: 0; max-height: 200px;"
        >
            <ul class="list-unstyled mb-0" x-ref="staticItems">
                {{ $slot }}
            </ul>
        </div>
    @endif
</div>
