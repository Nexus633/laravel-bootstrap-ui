@props([
    'name',
    'label' => null,
    'placeholder' => null,
    'hint' => null,
    'toggle' => true, // Soll das Auge angezeigt werden?
    'icon' => null,   // Optionales Icon ganz links (z.B. 'lock')
    'toggleShowIcon' => 'eye-slash',
    'toggleHideIcon' => 'eye'
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    $id = $attributes->get('id') ?? 'password-' . uniqid();

    // Icon Logic
    $toggleShowIcon = Icon::toClass($attributes->get('toggle:show') ?? $toggleShowIcon);
    $toggleHideIcon = Icon::toClass($attributes->get('toggle:hide') ?? $toggleHideIcon);
    $iconClass      = $icon ? Icon::toClass($icon) : null;

    // Attribute bereinigen
    $attributes = $attributes->except(['toggle:show', 'toggle:hide']);

    // Error Check
    $hasError = $name && $errors->has($name);
@endphp

{{-- 1. Der Wrapper kümmert sich um Label, Error und Abstände --}}
<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">

    {{-- 2. Alpine Scope für diesen spezifischen Input --}}
    <div x-data="{ show: false }">

        {{-- 3. Die Group hält alles zusammen --}}
        <x-bs::input.group :hasError="$hasError">

            {{-- A. Optionales Icon links --}}
            @if($iconClass)
                <span class="input-group-text">
                    <i class="{{ $iconClass }}"></i>
                </span>
            @endif

            {{-- B. Das eigentliche Input Feld --}}
            <input
                    id="{{ $id }}"
                    name="{{ $name }}"
                    {{-- Alpine steuert den Typ --}}
                    :type="show ? 'text' : 'password'"

                    @if($placeholder) placeholder="{{ $placeholder }}" @endif

                    {{-- Klassenlogik (muss hier manuell sein, da raw input) --}}
                    {{ $attributes->class(['form-control', 'is-invalid' => $hasError]) }}
            />

            {{-- C. Der Toggle Button (Rechts) --}}
            @if($toggle)
                <button
                        class="btn btn-outline-secondary"
                        type="button"
                        @click="show = !show"
                        tabindex="-1" {{-- Damit man nicht versehentlich drauf tabbt --}}
                        aria-label="Passwort anzeigen"
                >
                    <i class="bi" :class="show ? '{{ $toggleShowIcon }}' : '{{ $toggleHideIcon }}'"></i>
                </button>
            @endif

        </x-bs::input.group>
    </div>

</x-bs::input.wrapper>
