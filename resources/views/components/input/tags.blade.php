@props([
    'name' => null,
    'label' => null,
    'placeholder' => __('bs::bootstrap-ui.input.tags.placeholder'),
    'hint' => null,
    'max' => null,
    'variant' => 'secondary',
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make($name);
    $id = $attributes->getOrCreateId('tags-');
    $hasError = $field->hasError();

    $field->addClass('form-control', 'd-flex', 'flex-wrap', 'align-items-center', 'gap-1')
          ->addClassWhen($hasError, 'is-invalid');

    $isLightVariant = str_contains($variant, 'light') || str_contains($variant, 'warning') || str_contains($variant, 'info');
    $closeBtnClass = $isLightVariant ? '' : 'btn-close-white';

    $wireModel = $attributes->wire('model');
    $alpineModel = $wireModel->value()
        ? "\$wire.entangle('{$wireModel->value()}').live"
        : '[]';
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">
    <div
        class="{{ $field->getClasses() }}"
        style="min-height: 38px; cursor: text;"
        x-data="bsTags({
            model: {!! $alpineModel !!},
            max: {{ $max ?? 'null' }}
        })"
        @click="handleContainerClick()"
    >
        <template x-for="(tag, index) in tags" :key="index">
            <x-bs::badge
                :variant="$variant"
                pill
                class="d-flex align-items-center gap-1 user-select-none"
            >
                <x-bs::text span x-text="tag" />
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
            :placeholder="$placeholder"
            wire:ignore
            {{ $attributes->whereDoesntStartWith('wire:model') }}
        />

        <x-bs::text
            small
            italic
            variant="warning"
            x-show="isMaxReached()"
            class="ms-auto user-select-none"
            style="display: none;"
        >
            {{ __('bs::bootstrap-ui.input.tags.limit_reached', ['max' => $max]) }}
        </x-bs::text>
    </div>
</x-bs::input.wrapper>
