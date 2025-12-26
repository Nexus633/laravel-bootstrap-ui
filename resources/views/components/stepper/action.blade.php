@props([
    'finishLabel' => __('bs::bootstrap-ui.stepper.action.label_finish'),
    'finishLink' => null, // Optionaler Link am Ende
    'iconFinished' => 'check-lg',
    'iconNext' => 'arrow-right',
    'iconLast' => 'arrow-left'
])

@php
    $iconFinished = $attributes->get('icon:finished', $iconFinished);
    $iconNext = $attributes->get('icon:next', $iconNext);
    $iconLast = $attributes->get('icon:last', $iconLast);

    $attributes = $attributes->except(['icon:finished', 'icon:next', 'icon:last']);
@endphp

<div class="d-flex justify-content-between align-items-center mt-3">

    <x-bs::row g="3" class="w-100">
        <x-bs::row.col size="4">
            {{-- Button: Weiter --}}
            <x-bs::button
                :id="false"
                variant="outline-secondary"
                x-show="step > 1"
                @click="prev()"
                wire:key="btn-step-last" {{-- FIX: Livewire ignoriert jetzt ID-Änderungen --}}
            >
                @if($iconLast) <x-bs::icon :name="$iconLast" class="ms-1"/> @endif {{ __('bs::bootstrap-ui.stepper.action.button.previous') ?? 'Zurück' }}
            </x-bs::button>
        </x-bs::row.col>
        <x-bs::row.col size="4" class="text-center">
            {{ $slot }}
        </x-bs::row.col>
        <x-bs::row.col size="4" class="text-end">
            {{-- Button: Weiter --}}
            <x-bs::button
                :id="false"
                variant="primary"
                x-show="!isLast()"
                @click="next()"
                wire:key="btn-step-next" {{-- FIX: Livewire ignoriert jetzt ID-Änderungen --}}
            >
                {{ __('bs::bootstrap-ui.stepper.action.button.next') ?? 'Weiter' }} @if($iconNext) <x-bs::icon :name="$iconNext" class="ms-1"/> @endif
            </x-bs::button>

            {{-- Button: Fertig --}}
            <div x-show="isLast()" style="display: none;">
                @if(!$finishLink)
                    <x-bs::button
                        :id="false"
                        href="{{ $finishLink }}"
                        variant="success"
                        wire:key="btn-step-finished-link"
                    >
                        {{ $finishLabel }} @if($iconFinished) <x-bs::icon :name="$iconFinished" class="ms-1"/> @endif
                    </x-bs::button>
                @else
                    {{-- Wenn kein Link, feuern wir standardmäßig ein Livewire Event oder submit --}}
                    <x-bs::button
                        :id="false"
                        type="submit"
                        variant="success"
                        {{ $attributes }}
                        wire:key="btn-step-finished"
                    >
                        {{ $finishLabel }} @if($iconFinished) <x-bs::icon :name="$iconFinished" class="ms-1"/> @endif
                    </x-bs::button>
                @endif
            </div>
        </x-bs::row.col>
    </x-bs::row>
</div>
