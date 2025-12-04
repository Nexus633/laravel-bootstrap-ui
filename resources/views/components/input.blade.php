@props([
    'name',
    'label' => null,
    'type' => 'text',
    'hint' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,      // sm, lg
    // NEU: Wenn true, wird kein Container/Label gerendert (für komplexe Groups)
    'simple' => false,
])

@php
    $isPrependIcon = (bool)$attributes->get('icon:prepend');
    $isAppendIcon = (bool)$attributes->get('icon:append');

    $prepend = $attributes->get('icon:prepend') ?? $prepend;
    $append = $attributes->get('icon:append') ?? $append;

    $attributes = $attributes->except(['icon:append', 'icon:prepend']);

    $id = $attributes->get('id') ?? 'input-' . uniqid();
    $hasError = $name && $errors->has($name);

    // Checken ob wir interne Groups brauchen (nur wenn NICHT simple mode)
    $hasInternalGroup = !$simple && ($prepend || $append || $slot->isNotEmpty());

    $inputClasses = 'form-control';
    if($hasError) $inputClasses .= ' is-invalid';
    if($size) $inputClasses .= ' form-control-' . $size;
@endphp

@if($simple)
    {{--
        SIMPLE MODE: Nur das Input-Feld (für komplexe Input-Groups).
        Kein Label, kein mb-3 Wrapper.
    --}}
    <input
            id="{{ $id }}"
            type="{{ $type }}"
            name="{{ $name }}"
            {{ $attributes->merge(['class' => $inputClasses]) }}
    />
@else
    {{-- STANDARD MODE: Mit Label, Wrapper und internen Addons --}}
    <div class="mb-3">
        @if($label)
            <label for="{{ $id }}" class="form-label">{{ $label }}</label>
        @endif

        @if($hasInternalGroup)
            <div class="input-group {{ $hasError ? 'is-invalid' : '' }} {{ $size ? 'input-group-'.$size : '' }}">
                @if($prepend)
                    <span class="input-group-text">
                    @if($isPrependIcon)
                        <i class="bi {{ $prepend }}" aria-hidden="true"></i>
                    @else
                        {!! $prepend !!}
                    @endif
                    </span>
                @endif

                <input
                    id="{{ $id }}"
                    type="{{ $type }}"
                    name="{{ $name }}"
                    {{ $attributes->merge(['class' => $inputClasses]) }}
                />

                @if($append)
                    <span class="input-group-text">
                        @if($isAppendIcon)
                            <i class="bi {{ $append }}" aria-hidden="true"></i>
                        @else
                            {!! $append !!}
                        @endif
                    </span>
                @endif
            </div>
        @else
            <input
                id="{{ $id }}"
                type="{{ $type }}"
                name="{{ $name }}"
                {{ $attributes->merge(['class' => $inputClasses]) }}
            />
        @endif

        @if($hasError)
            <div class="invalid-feedback">{{ $errors->first($name) }}</div>
        @elseif($hint)
            <div class="form-text text-muted">{{ $hint }}</div>
        @endif
    </div>
@endif
