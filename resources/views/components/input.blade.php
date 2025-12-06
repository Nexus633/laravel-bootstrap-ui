@props([
    'name',
    'label' => null,
    'type' => 'text',
    'hint' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,
    'simple' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;
    $id = $attributes->get('id') ?? 'input-' . uniqid();

    // Floating Check
    $isFloating = filter_var($attributes->get('label:floating'), FILTER_VALIDATE_BOOLEAN);

    // Icon Logic
    $iconPrepend = Icon::toClass($attributes->get('icon:prepend'));
    $iconAppend  = Icon::toClass($attributes->get('icon:append'));

    // Attribute bereinigen
    $attributes = $attributes->except(['icon:prepend', 'icon:append', 'label:floating']);

    $hasError = $name && $errors->has($name);
    // Basis-Klassen
    $baseClasses = [
        'form-control',
        'is-invalid' => $hasError,
    ];

    if ($size) {
        $baseClasses[] = 'form-control-' . $size;
    }

    // Check für interne Groups (Icon, Prepend, Append oder Slot)
    $hasInternalGroup = !$simple && ($prepend || $append || $iconPrepend || $iconAppend || $slot->isNotEmpty());
@endphp

@if($simple)
    {{-- SIMPLE MODE --}}
    <input
            id="{{ $id }}"
            type="{{ $type }}"
            name="{{ $name }}"
            {{ $attributes->class($baseClasses) }}
    />
@else
    {{-- WRAPPER MODE --}}
    <div class="mb-3">

        {{--
            LABEL FIX:
            Wir zeigen das Label IMMER oben an, solange es gesetzt ist
            UND wir nicht im Floating-Mode sind.
            (Ob es eine Group ist oder nicht, ist egal).
        --}}
        @if($label && !$isFloating)
            <label for="{{ $id }}" class="form-label">{{ $label }}</label>
        @endif

        {{-- LAYOUT LOGIK --}}

        @if($hasInternalGroup)
            {{-- CASE: INPUT GROUP --}}
            <div class="input-group {{ $hasError ? 'is-invalid' : '' }} {{ $size ? 'input-group-'.$size : '' }}">

                {{-- PREPEND --}}
                @if($prepend || $iconPrepend)
                    <span class="input-group-text">
                        {{-- Kleiner Hinweis: Bei icons nur den Namen ohne 'bi-' übergeben, z.B. 'person' statt 'bi-person' --}}
                        @if($iconPrepend) <i class="{{ $iconPrepend }}"></i> @else {!! $prepend !!} @endif
                    </span>
                @endif

                {{-- CORE INPUT --}}
                @if($isFloating)
                    {{-- CASE: FLOATING INSIDE GROUP --}}
                    <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                        <input
                                id="{{ $id }}"
                                type="{{ $type }}"
                                name="{{ $name }}"
                                placeholder="{{ $label }}"
                                {{ $attributes->class($baseClasses) }}
                        />
                        <label for="{{ $id }}">{{ $label }}</label>
                    </div>
                @else
                    {{-- CASE: STANDARD GROUP --}}
                    <input
                            id="{{ $id }}"
                            type="{{ $type }}"
                            name="{{ $name }}"
                            {{ $attributes->class($baseClasses) }}
                    />
                @endif

                {{-- APPEND --}}
                @if($append || $iconAppend)
                    <span class="input-group-text">
                        @if($iconAppend) <i class="{{ $iconAppend }}"></i> @else {!! $append !!} @endif
                    </span>
                @endif
            </div>

        @elseif($isFloating)
            {{-- CASE: PURE FLOATING --}}
            <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                <input
                        id="{{ $id }}"
                        type="{{ $type }}"
                        name="{{ $name }}"
                        placeholder="{{ $label }}"
                        {{ $attributes->class($baseClasses) }}
                />
                <label for="{{ $id }}">{{ $label }}</label>
            </div>

        @else
            {{-- CASE: STANDARD INPUT --}}
            <input
                    id="{{ $id }}"
                    type="{{ $type }}"
                    name="{{ $name }}"
                    {{ $attributes->class($baseClasses) }}
            />
        @endif

        {{-- VALIDATION & HINTS --}}
        @if($hasError)
            <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
        @elseif($hint)
            <div class="form-text text-muted">{{ $hint }}</div>
        @endif

    </div>
@endif
