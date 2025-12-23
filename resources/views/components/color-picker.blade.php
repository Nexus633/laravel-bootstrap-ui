@props([
    'name',
    'label' => null,
    'placeholder' => 'Farbe oder Name (z.B. red)',
    'presets' => [],
    'floating' => false,
    'readonly' => false,
    'disabled' => false,
    'hint' => null,
])

@php
    $id = $attributes->get('id') ?? 'cp-' . uniqid();

    // Livewire Logik: Wir bereiten das Model vor
    $wireModel = $attributes->wire('model');

    // Entweder ein Livewire-Objekt (@entangle) oder der statische Wert als String
    if ($wireModel->directive()) {
        $alpineValue = "window.Livewire.find('{$this->getId()}').entangle('{$wireModel->value()}')";
    } else {
        // Falls kein Livewire: Nutze den 'value' prop oder default '#000000'
        // json_encode sorgt dafür, dass Strings sicher an JS übergeben werden
        $val = $attributes->get('value') ?? '#000000';
        $alpineValue = json_encode($val);
    }
@endphp

<div
    {{-- HIER IST DER CLEANUP: Wir rufen nur noch die JS-Funktion auf --}}
    x-data="bsColorPicker({{ $alpineValue }})"
    class="mb-3"
>
    <x-bs::input.wrapper
        :label="$floating ? null : $label"
        :id="$id"
        :name="$name"
        :hint="$hint"
    >
        <x-bs::input.group>

            {{-- A. COLOR TRIGGER --}}
            <x-bs::input-group.text class="p-1" style="min-width: 3rem;">
                <x-bs::input
                    simple
                    name=""
                    type="color"
                    class="form-control-color border-0 p-0 m-0 w-100 h-100 shadow-none"
                    style="cursor: pointer;"
                    x-model="pickerValue"
                    @input="updateFromPicker($event.target.value)"
                    title="{{ __('Farbe wählen') }}"
                    :disabled="$disabled"
                    :readonly="$readonly"
                />
            </x-bs::input-group.text>

            {{-- B. TEXT INPUT --}}
            @if($floating)
                <div class="form-floating position-relative flex-grow-1">
                    <x-bs::input
                        simple
                        type="text"
                        :id="$id"
                        :name="$name"
                        x-model.debounce.300ms="textValue"
                        placeholder="{{ $placeholder }}"
                        class="font-monospace"
                        :readonly="$readonly"
                        :disabled="$disabled"
                    />
                    <x-bs::text label for="{{ $id }}">{{ $label }}</x-bs::text>
                </div>
            @else
                <x-bs::input
                    simple
                    type="text"
                    :id="$id"
                    :name="$name"
                    x-model.debounce.300ms="textValue"
                    placeholder="{{ $placeholder }}"
                    class="font-monospace"
                    :readonly="$readonly"
                    :disabled="$disabled"
                />
            @endif

        </x-bs::input.group>

        {{-- C. PRESETS --}}
        @if(count($presets) > 0)
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($presets as $preset)
                    <x-bs::button
                        type="button"
                        class="p-0 border rounded-circle shadow-sm position-relative"
                        style="width: 24px; height: 24px; background-color: {{ $preset }};"
                        {{-- Klick setzt den Text-Wert --}}
                        @click="textValue = '{{ $preset }}'"
                        title="{{ $preset }}"
                        {{-- Aktiven Status prüfen --}}
                        x-bind:class="(textValue && textValue.toLowerCase() === '{{ strtolower($preset) }}') ? 'ring-2 ring-primary border-primary' : ''"
                        :disabled="$disabled"
                        variant="light"
                    />
                @endforeach
            </div>
        @endif

    </x-bs::input.wrapper>
</div>
