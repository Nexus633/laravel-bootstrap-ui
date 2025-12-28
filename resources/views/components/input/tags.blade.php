@props([
    'name' => null, // Optional, falls nur wire:model genutzt wird
    'label' => null,
    'placeholder' => __('bs::bootstrap-ui.input.tags.placeholder'),
    'hint' => null,
    'max' => null,
    'variant' => 'secondary',
])

@php
    use Illuminate\Support\Str;

    // ID Generierung
    $id = $attributes->get('id') ?? ($name ? 'tags-' . Str::slug($name) : 'tags-' . uniqid());

    // Fehlerbehandlung
    $hasError = $name && $errors->has($name);

    // Styling Klassen
    $containerClasses = [
        'form-control',
        'd-flex',
        'flex-wrap',
        'align-items-center',
        'gap-1',
        $hasError ? 'is-invalid' : null
    ];

    // Close Button Farbe basierend auf Variant
    $isLightVariant = str_contains($variant, 'light') ||
                      str_contains($variant, 'warning') ||
                      str_contains($variant, 'info');
    $closeBtnClass = $isLightVariant ? '' : 'btn-close-white';

    // Model Setup: Wir müssen entscheiden ob Livewire Entangle oder Array
    $wireModel = $attributes->wire('model');
    // WICHTIG: Wir geben den rohen String für Alpine zurück, nicht das PHP Ergebnis
    $alpineModel = $wireModel->value()
        ? "\$wire.entangle('{$wireModel->value()}').live"
        : '[]';
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">
    <div
        class="{{ implode(' ', array_filter($containerClasses)) }}"
        style="min-height: 38px; cursor: text;"

            {{-- Logik in bsTags --}}
        x-data="bsTags({
            model: {!! $alpineModel !!},
            max: {{ $max ?? 'null' }}
        })"

            @click="handleContainerClick()"
    >
        {{-- Tags Loop --}}
        <template x-for="(tag, index) in tags" :key="index">
            <x-bs::badge
                :variant="$variant"
                pill
                class="d-flex align-items-center gap-1 user-select-none"
            >
                <x-bs::text span x-text="tag" />
                {{-- Close Button --}}
                <x-bs::button.close
                    @click.stop="remove(index)"
                    style="width: 0.5em; height: 0.5em; font-size: 0.8em;"
                    class="{{ $closeBtnClass }}"
                />
            </x-bs::badge>
        </template>

        <x-bs::input
            simple
            name=""
            x-show="!isMaxReached()"
            x-ref="tagInput"
            :id="$id"
            wire:key="tag-input-{{ $id }}"
            x-model="newTag"

            @keydown.enter.prevent="add()"
            @keydown.comma.prevent="add()"
            @keydown.backspace="removeLast()"

            class="border-0 p-0 bg-transparent flex-grow-1"
            style="outline: none; box-shadow: none; min-width: 80px;"
            placeholder="{{ $placeholder }}"
            {{--
               Wire:ignore ist wichtig, damit Livewire beim Re-Render
               nicht den Fokus klaut oder den Input resettet während man tippt
            --}}
            wire:ignore
            {{ $attributes->whereDoesntStartWith('wire:model') }}
        />

        {{-- LIMIT MELDUNG --}}
        <x-bs::text
            small
            italic
            variant="warning"
            x-show="isMaxReached()"
            class="ms-auto user-select-none"
            style="display: none;" {{-- Damit es beim Laden nicht kurz aufblitzt --}}
        >
            {{ __('bs::bootstrap-ui.input.tags.limit_reached', ['max' => $max]) }}
        </x-bs::text>
    </div>
</x-bs::input.wrapper>
