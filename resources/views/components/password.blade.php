@props([
    'name',
    'label' => null,
    'placeholder' => null,
    'hint' => null,
    'toggle' => true, // Soll das Auge angezeigt werden?
    'icon' => null,
    'toggleShowIcon' => 'eye-slash',
    'toggleHideIcon' => 'eye'
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;
    $id = $attributes->get('id') ?? 'password-'. uniqid();
    $toggleShowIcon = $attributes->get('toggle:show') ?? $toggleShowIcon;
    $toggleHideIcon = $attributes->get('toggle:hide') ?? $toggleHideIcon;

    $toggleShowIcon = Icon::toClass($toggleShowIcon);
    $toggleHideIcon = Icon::toClass($toggleHideIcon);

    $attributes = $attributes->except(['toggle:show', 'toggle:hide']);




    $hasError = $name && $errors->has($name);
@endphp

<div
        class="mb-3"
        x-data="{ show: false }" {{-- Alpine State für Sichtbarkeit --}}
>
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
        </label>
    @endif

    <div class="@if($toggle) input-group @endif {{ $hasError ? 'is-invalid' : '' }}">
        @if($icon)
            <span class="input-group-text">
                <i class="bi bi-{{ $icon }}" aria-hidden="true"></i>
            </span>
        @endif
        {{-- Der Input --}}
        <input
                id="{{ $id }}"
                {{-- Der Typ wechselt basierend auf dem Alpine State --}}
                :type="show ? 'text' : 'password'"
                name="{{ $name }}"
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                {{ $attributes->class(['form-control', 'is-invalid' => $hasError]) }}
        />

        {{-- Der Toggle Button (nur wenn aktiviert) --}}
        @if($toggle)
            <button
                    class="btn btn-outline-secondary"
                    type="button"
                    @click="show = !show"
                    aria-label="Passwort anzeigen"
            >
                {{-- Icon wechselt (Auge offen / durchgestrichen) --}}
                <i class="bi " :class="show ? '{{ $toggleShowIcon }}' : '{{ $toggleHideIcon }}'"></i>
            </button>
        @endif
    </div>

    {{-- Error Handling --}}
    @if($hasError)
        <div class="invalid-feedback d-block">
            {{ $errors->first($name) }}
        </div>
    @elseif($hint)
        <div class="form-text text-muted">
            {{ $hint }}
        </div>
    @endif
</div>
