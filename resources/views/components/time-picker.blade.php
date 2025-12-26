@props([
    'name',
    'label' => null,
    'labelStart' => null,
    'labelEnd' => null,
    'hint' => null,
    'placeholder' => null,
    'min' => '00:00',
    'max' => '23:59',
    'interval' => 30,
    'range' => false,
    'separate' => false,
    'disable' => [],
    'icon' => 'clock',
    'format' => '24',
    'iconSeparator' => 'arrow-right-short',
])

@php
    $id = $attributes->get('id', 'timepicker-' . uniqid());
    $wireModel = $attributes->wire('model');
    $pickerId = 'tp-' . md5($name . $id);

    // Config vorbereiten
    $entangleStr = $wireModel->value() ? "\$wire.entangle('{$wireModel->value()}').live" : 'null';
    $mode = $range ? 'range' : 'single';
    $disableJson = json_encode($disable);

    // Icons
    $iconPrepend = $attributes->get('icon:prepend', $icon);
    if ($iconPrepend === 'false' || $iconPrepend === false) $iconPrepend = null;
    $iconAppend = $attributes->get('icon:append');
    $separator = $attributes->get('icon:separator') ?? $iconSeparator;

    $iconPrependStart = $attributes->get('icon:prepend:start', $icon);
    $iconAppendStart = $attributes->get('icon:append:start');

    $iconPrependEnd = $attributes->get('icon:prepend:end', $icon);
    $iconAppendEnd = $attributes->get('icon:append:end');

    $attributes = $attributes->except(['icon:prepend', 'icon:append', 'icon:prepend:start', 'icon:append:start', 'icon:prepend:end', 'icon:append:end', 'icon:separator'])
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">

    {{-- Root Element mit Alpine --}}
    <div
        class="position-relative"
        id="{{ $pickerId }}"
        x-data="bsTimePicker({
            model: {{ $entangleStr }},
            min: '{{ $min }}',
            max: '{{ $max }}',
            interval: {{ $interval }},
            mode: '{{ $mode }}',
            disable: {{ $disableJson }},
            format: '{{ $format }}'
        })"
        @click.outside="close()"
    >
        {{-- 1. Inputs Rendering (Ausgelagert) --}}
        <x-bs::time-picker.inputs
            :id="$id"
            :name="$name"
            :range="$range"
            :separate="$separate"
            :labelStart="$labelStart"
            :labelEnd="$labelEnd"
            :placeholder="$placeholder"
            :iconPrepend="$iconPrepend"
            :iconAppend="$iconAppend"
            :separator="$separator"
            :iconPrependStart="$iconPrependStart"
            :iconAppendStart="$iconAppendStart"
            :iconPrependEnd="$iconPrependEnd"
            :iconAppendEnd="$iconAppendEnd"
            {{ $attributes }}
        />

        {{-- 2. Hidden Input (Form Support) --}}
        <x-bs::input type="hidden" name="{{ $name }}" value="JSON.stringify(value)" />

        {{-- 3. Popup Dialog (Ausgelagert) --}}
        <x-bs::time-picker.dialog />
    </div>
</x-bs::input.wrapper>
