@props([
    'name',
    'label' => null,
    'type' => 'text',
    'hint' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,
    'simple' => false, // true = Nur <input> Tag ohne alles
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    $id = $attributes->get('id') ?? 'input-' . uniqid();

    // Floating Logic extrahieren
    $isFloating = filter_var($attributes->get('label:floating'), FILTER_VALIDATE_BOOLEAN);
    $attributes = $attributes->except(['label:floating']);

    // Error Check
    $hasError = $name && $errors->has($name);

    // Basis Klassen für den <input> Tag
    $inputClasses = ['form-control'];
    if ($hasError) $inputClasses[] = 'is-invalid';
    if ($size)     $inputClasses[] = 'form-control-' . $size;

    // Icons auflösen
    $iconPrepend = Icon::toClass($attributes->get('icon:prepend'));
    $iconAppend  = Icon::toClass($attributes->get('icon:append'));
    $attributes = $attributes->except(['icon:prepend', 'icon:append']);

    // Check: Brauchen wir eine Input-Group?
    $hasGroup = ($prepend || $append || $iconPrepend || $iconAppend);
@endphp

@if($simple)
    {{-- RAW MODE: Nur der Tag (für Nutzung IN Gruppen) --}}
    <input
            id="{{ $id }}"
            type="{{ $type }}"
            name="{{ $name }}"
            {{ $attributes->class($inputClasses) }}
    />
@else
    {{-- WRAPPER MODE: Standard Nutzung --}}
    <x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint" :floating="$isFloating">

        @if($hasGroup)
            {{-- OPTION A: INPUT GROUP --}}
            <x-bs::input.group :size="$size">

                {{-- Prepend --}}
                @if($prepend || $iconPrepend)
                    <span class="input-group-text">
                         @if($iconPrepend) <i class="{{ $iconPrepend }}"></i> @else {!! $prepend !!} @endif
                    </span>
                @endif

                {{-- Input (ggf. Floating inside Group) --}}
                @if($isFloating)
                    <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" placeholder="{{ $label }}" {{ $attributes->class($inputClasses) }} />
                        <label for="{{ $id }}">{{ $label }}</label>
                    </div>
                @else
                    <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" {{ $attributes->class($inputClasses) }} />
                @endif

                {{-- Append --}}
                @if($append || $iconAppend)
                    <span class="input-group-text">
                        @if($iconAppend) <i class="{{ $iconAppend }}"></i> @else {!! $append !!} @endif
                    </span>
                @endif
            </x-bs::input.group>

        @elseif($isFloating)
            {{-- OPTION B: PURE FLOATING --}}
            <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" placeholder="{{ $label }}" {{ $attributes->class($inputClasses) }} />
                <label for="{{ $id }}">{{ $label }}</label>
            </div>

        @else
            {{-- OPTION C: STANDARD INPUT --}}
            <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" {{ $attributes->class($inputClasses) }} />
        @endif

    </x-bs::input.wrapper>
@endif
