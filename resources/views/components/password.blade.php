@props([
    'name' => null,
    'label' => null,
    'placeholder' => null,
    'hint' => null,
    'toggle' => true,
    'icon' => null,
    'toggleShowIcon' => 'eye-slash',
    'toggleHideIcon' => 'eye'
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $field = BootstrapUi::make($name);

    // Pluck custom attributes
    $toggleShowIcon = Icon::toClass($attributes->pluck('toggle:show', $toggleShowIcon));
    $toggleHideIcon = Icon::toClass($attributes->pluck('toggle:hide', $toggleHideIcon));

    $id = $attributes->getOrCreateId('password-');
    $hasError = $field->hasError();
    $iconClass = $icon ? Icon::toClass($icon) : null;
    
    $field->addClass('form-control')
          ->addClassWhen($hasError, 'is-invalid');
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">
    <div x-data="{ show: false }">
        <x-bs::input.group>
            @if($iconClass)
                <span class="input-group-text">
                    <i class="{{ $iconClass }}"></i>
                </span>
            @endif

            <input
                id="{{ $id }}"
                @if($name) name="{{ $name }}" @endif
                :type="show ? 'text' : 'password'"
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                {{ $attributes->class($field->getClasses()) }}
            />

            @if($toggle)
                <button
                    class="btn btn-outline-secondary"
                    type="button"
                    @click="show = !show"
                    tabindex="-1"
                    aria-label="Passwort anzeigen"
                >
                    <i class="bi" :class="show ? '{{ $toggleShowIcon }}' : '{{ $toggleHideIcon }}'"></i>
                </button>
            @endif
        </x-bs::input.group>
    </div>
</x-bs::input.wrapper>
