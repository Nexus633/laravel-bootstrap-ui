@props([
    'name',
    'label' => null,
    'placeholder' => 'Tag hinzufügen...',
    'hint' => null,
    'max' => null,
    'variant' => 'secondary',
])

@php
    use Illuminate\Support\Str;
    // ID & Error Setup
    $id = $attributes->get('id') ?? ($name ? 'tags-' . Str::slug($name) : 'tags-' . uniqid());
    $wireModel = $attributes->wire('model');
    $hasError = $name && $errors->has($name);

    $containerClasses = [
        'form-control',
        'd-flex',
        'flex-wrap',
        'align-items-center',
        'gap-1',
        $hasError ? 'is-invalid' : null
    ];

    $isLightVariant = str_contains($variant, 'light') ||
                      str_contains($variant, 'warning') ||
                      str_contains($variant, 'info');
    $closeBtnClass = $isLightVariant ? '' : 'btn-close-white';

    $tagsInit = $wireModel->value()
        ? "\$wire.entangle('{$wireModel->value()}').live"
        : '[]';
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">

    <div
        class="{{ implode(' ', array_filter($containerClasses)) }}"
        style="min-height: 38px; cursor: text;"

        {{-- Klick nur fokussieren, wenn NICHT voll --}}
        @click="if(!isMaxReached()) $refs.tagInput.focus()"

        x-data="{
            tags: {!! $tagsInit !!},
            newTag: '',
            maxTags: {{ $max ?? 'null' }},

            addTag() {
                let tag = this.newTag.trim();
                if (tag !== '' && !this.tags.includes(tag)) {
                    if (this.maxTags === null || this.tags.length < this.maxTags) {
                        this.tags.push(tag);
                    }
                }
                this.newTag = '';
                this.$nextTick(() => {
                    if (!this.isMaxReached()) $refs.tagInput.focus();
                });
            },

            removeTag(index) {
                this.tags.splice(index, 1);
                this.$nextTick(() => {
                    $refs.tagInput.focus();
                });
            },

            removeLastTag() {
                if (this.newTag === '' && this.tags.length > 0) {
                    this.tags.pop();
                }
            },

            isMaxReached() {
                return this.maxTags !== null && this.tags.length >= this.maxTags;
            }
        }"
    >
        {{-- Tags Loop --}}
        <template x-for="(tag, index) in tags" :key="index">
            <x-bs::badge
                :variant="$variant"
                pill
                class="d-flex align-items-center gap-1 user-select-none"
            >
                <span x-text="tag"></span>
                <x-bs::button.close
                    @click.stop="removeTag(index)"
                    style="width: 0.5em; height: 0.5em; font-size: 0.8em;"
                    class="{{ $closeBtnClass }}"
                />
            </x-bs::badge>
        </template>

        {{--
            INPUT FELD
            Wir nutzen x-show, um es komplett auszublenden, wenn Limit erreicht.
            Das verhindert auch den weißen Rahmen/Cursor.
        --}}
        <input
            x-show="!isMaxReached()"
            x-ref="tagInput"
            id="{{ $id }}"
            wire:key="tag-input-{{ $id }}"
            type="text"
            x-model="newTag"

            @keydown.enter.prevent="addTag()"
            @keydown.comma.prevent="addTag()"
            @keydown.backspace="removeLastTag()"

            class="border-0 p-0 bg-transparent flex-grow-1"
            style="outline: none; box-shadow: none; min-width: 80px;"
            placeholder="{{ $placeholder }}"

            wire:ignore
            {{ $attributes->whereDoesntStartWith('wire:model') }}
        >

        {{-- LIMIT MELDUNG --}}
        <span
            x-show="isMaxReached()"
            x-transition.opacity
            class="text-warning small fst-italic ms-auto user-select-none"
        >
            {{ __('bs::bootstrap-ui.input.tags.limit_reached', ['max' => $max]) }}
        </span>

    </div>

</x-bs::input.wrapper>
