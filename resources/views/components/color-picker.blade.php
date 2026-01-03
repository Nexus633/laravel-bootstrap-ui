@props([
    'name' => null,
    'label' => null,
    'placeholder' => 'Farbe oder Name (z.B. red)',
    'presets' => [],
    'floating' => false,
    'readonly' => false,
    'disabled' => false,
    'hint' => null,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    
    $field = BootstrapUi::make($name);
    $id = $attributes->getOrCreateId('cp-');
    $hasError = $field->hasError();

    // Livewire/Alpine logic remains the same
    $wireModel = $attributes->wire('model');
    if ($wireModel->directive()) {
        $alpineValue = "window.Livewire.find('{$this->getId()}').entangle('{$wireModel->value()}')";
    } else {
        $val = $attributes->get('value', '#000000');
        $alpineValue = json_encode($val);
    }
    
    // Class building for the text input
    $inputField = BootstrapUi::make($name)
                    ->addClass('font-monospace')
                    ->addClassWhen($hasError, 'is-invalid');
@endphp

<div
    x-data="bsColorPicker({{ $alpineValue }})"
    class="mb-3"
>
    <x-bs::input.wrapper
        :label="$floating ? null : $label"
        :id="$id"
        :name="$name"
        :hint="$hint"
    >
        <x-bs::input.group :class="$hasError ? 'is-invalid' : ''">

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
                        :placeholder="$placeholder"
                        class="{{ $inputField->getClasses() }}"
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
                    :placeholder="$placeholder"
                    class="{{ $inputField->getClasses() }}"
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
                        @click="textValue = '{{ $preset }}'"
                        title="{{ $preset }}"
                        x-bind:class="(textValue && textValue.toLowerCase() === '{{ strtolower($preset) }}') ? 'ring-2 ring-primary border-primary' : ''"
                        :disabled="$disabled"
                        variant="light"
                    />
                @endforeach
            </div>
        @endif

    </x-bs::input.wrapper>
</div>
