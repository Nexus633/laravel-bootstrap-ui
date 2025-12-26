@props([
    'name',
    'label' => null,
    'type' => 'text',
    'multiple' => false,
    'hint' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,
    'simple' => false, // true = Nur <input> Tag ohne alles
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    $id = $attributes->get('id', 'input-' . uniqid());

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

    if($multiple && $type !== 'file'){
        $multiple = false;
    }

@endphp

@if($simple)
    {{-- RAW MODE: Nur der Tag (für Nutzung IN Gruppen) --}}
    <input
        id="{{ $id }}"
        type="{{ $type }}"
        name="{{ $name }}"
        @if($multiple) multiple @endif
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
                    <x-bs::input-group.text>
                         @if($iconPrepend) <x-bs::icon :name="$iconPrepend" /> @else {!! $prepend !!} @endif
                    </x-bs::input-group.text>
                @endif

                {{-- Input (ggf. Floating inside Group) --}}
                @if($isFloating)
                    <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                        <input id="{{ $id }}" type="{{ $type }}" @if($multiple) multiple @endif name="{{ $name }}" placeholder="{{ $label }}" {{ $attributes->class($inputClasses) }} />
                        <x-bs::text label for="{{ $id }}">{{ $label }}</x-bs::text>
                    </div>
                @else
                    <input id="{{ $id }}" type="{{ $type }}" @if($multiple) multiple @endif name="{{ $name }}" {{ $attributes->class($inputClasses) }} />
                @endif

                {{-- Append --}}
                @if($append || $iconAppend)
                    <x-bs::input-group.text>
                        @if($iconAppend) <x-bs::icon :name="$iconAppend" /> @else {!! $append !!} @endif
                    </x-bs::input-group.text>
                @endif
            </x-bs::input.group>

        @elseif($isFloating)
            {{-- OPTION B: PURE FLOATING --}}
            <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                <input id="{{ $id }}" type="{{ $type }}" @if($multiple) multiple @endif name="{{ $name }}" placeholder="{{ $label }}" {{ $attributes->class($inputClasses) }} />
                <x-bs::text label for="{{ $id }}">{{ $label }}</x-bs::text>
            </div>

        @else
            {{-- OPTION C: STANDARD INPUT --}}
            <input id="{{ $id }}" type="{{ $type }}" @if($multiple) multiple @endif name="{{ $name }}" {{ $attributes->class($inputClasses) }} />
        @endif

    </x-bs::input.wrapper>
@endif
