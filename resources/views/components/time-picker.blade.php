@props([
    'name' => null,
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
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make($name);
    $id = $attributes->getOrCreateId('timepicker-');
    $hasError = $field->hasError();

    $wireModel = $attributes->wire('model');
    $pickerId = 'tp-' . md5(($name ?? 'tp') . $id);

    $entangleStr = $wireModel->value() ? "\$wire.entangle('{$wireModel->value()}').live" : 'null';
    $mode = $range ? 'range' : 'single';
    $disableJson = json_encode($disable);

    $iconPrepend = $attributes->pluck('icon:prepend', $icon);
    if ($iconPrepend === 'false' || $iconPrepend === false) $iconPrepend = null;
    $iconAppend = $attributes->pluck('icon:append');
    $separator = $attributes->pluck('icon:separator', $iconSeparator);
    $iconPrependStart = $attributes->pluck('icon:prepend:start', $icon);
    $iconAppendStart = $attributes->pluck('icon:append:start');
    $iconPrependEnd = $attributes->pluck('icon:prepend:end', $icon);
    $iconAppendEnd = $attributes->pluck('icon:append:end');

    if ($hasError) {
        $attributes = $attributes->merge(['class' => 'is-invalid']);
    }
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">
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

        @if($name)
            <x-bs::input type="hidden" :name="$name" x-bind:value="JSON.stringify(value)" />
        @endif

        <x-bs::time-picker.dialog />
    </div>
</x-bs::input.wrapper>
