@props([
    'name',
    'label' => null,
    'rows' => 3,
    'hint' => null,
    'simple' => false,
    'resize' => 'vertical', // 'vertical', 'horizontal', 'none', 'both'
])

@php
    // ID generieren falls nicht übergeben
    $id = $attributes->get('id') ?? 'textarea-' . uniqid();

    // Floating Logic extrahieren (Konsistent mit Input-Komponente)
    $isFloating = filter_var($attributes->get('label:floating'), FILTER_VALIDATE_BOOLEAN);

    // Das Attribut entfernen, damit es nicht im HTML landet
    $attributes = $attributes->except(['label:floating']);

    // Error Logik
    $hasError = $name && $errors->has($name);

    // Basis CSS Klassen
    $classes = ['form-control'];
    if ($hasError) $classes[] = 'is-invalid';
@endphp

@if($simple)
    {{-- SIMPLE MODE: Nur das Element (z.B. für Input Groups) --}}
    <textarea
            name="{{ $name }}"
            id="{{ $id }}"
            rows="{{ $rows }}"
            style="resize: {{ $resize }}"
        {{ $attributes->class($classes) }}
    >{{ $slot }}</textarea>

@else
    {{-- WRAPPER MODE: Standard Nutzung mit Label/Error/Hint --}}
    <x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint" :floating="$isFloating">

        @if($isFloating)
            {{-- Floating Label Implementation --}}
            {{-- Hinweis: Floating Labels benötigen eine definierte Höhe via CSS, da 'rows' oft ignoriert wird --}}
            <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                <textarea
                        name="{{ $name }}"
                        id="{{ $id }}"
                        placeholder="{{ $label }}"
                        style="resize: {{ $resize }}; height: calc(2.5rem + {{ $rows * 1.5 }}rem);"
                    {{ $attributes->class($classes) }}
                >{{ $slot }}</textarea>
                <label for="{{ $id }}">{{ $label }}</label>
            </div>
        @else
            {{-- Standard Implementation --}}
            <textarea
                    name="{{ $name }}"
                    id="{{ $id }}"
                    rows="{{ $rows }}"
                    style="resize: {{ $resize }}"
                {{ $attributes->class($classes) }}
            >{{ $slot }}</textarea>
        @endif

    </x-bs::input.wrapper>
@endif
