@props([
    'name',
    'label' => null,
    'hint' => null,
    'placeholder' => null,
    'min' => null,
    'max' => null,
    'range' => false,
    'separate' => false,
    'labelStart' => null,
    'labelEnd' => null,
    'disable' => [],
    'icon' => 'calendar-event', // Standard Prepend Icon
    'double' => false,
])

@php
    $id = $attributes->get('id') ?? 'datepicker-' . uniqid();
    $wireModel = $attributes->wire('model');
    $pickerId = 'dp-' . md5($name . $id);

    $entangleStr = $wireModel->value() ? "\$wire.entangle('{$wireModel->value()}').live" : 'null';
    $mode = $range ? 'range' : 'single';
    $placeholder = $placeholder ?? ($range ? __('bs::bootstrap-ui.date-picker.inputs.from_to') : __('bs::bootstrap-ui.date-picker.inputs.select_date'));

    // JSON Data
    $disableJson = json_encode($disable);
    $transMonths = __('bs::bootstrap-ui.date-picker.months');
    $monthsJson = json_encode(is_array($transMonths) ? $transMonths : ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez']);
    $transDays = __('bs::bootstrap-ui.date-picker.days');
    $daysJson = json_encode(is_array($transDays) ? $transDays : ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So']);

    // --- ICON LOGIC ---
    // 1. Prepend: Prop 'icon' oder Attribut 'icon:prepend'
    $iconPrepend = $attributes->get('icon:prepend') ?? $icon;
    if ($iconPrepend === 'false' || $iconPrepend === false) $iconPrepend = null;

    // 2. Append: Attribut 'icon:append'
    $iconAppend = $attributes->get('icon:append');

    $iconPrependStart = $attributes->get('icon:prepend:start')  ?? $icon;
    $iconAppendStart = $attributes->get('icon:append:start');

    $iconPrependEnd = $attributes->get('icon:prepend:end')  ?? $icon;
    $iconAppendEnd = $attributes->get('icon:append:end');

    $attributes = $attributes->except(['icon:prepend', 'icon:append', 'icon:prepend:start', 'icon:append:start', 'icon:prepend:end', 'icon:append:end'])
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">

    <div
        class="position-relative"
        id="{{ $pickerId }}"
        x-data='bsDatePicker({
            model: {{ $entangleStr }},
            min: "{{ $min }}",
            max: "{{ $max }}",
            mode: "{{ $mode }}",
            double: {{ $double ? "true" : "false" }},
            disable: {!! $disableJson !!},
            months: {!! $monthsJson !!},
            days: {!! $daysJson !!}
        })'
        @click.outside="close()"
    >
        {{-- 1. Inputs (Icons übergeben) --}}
        <x-bs::date-picker.inputs
            :id="$id"
            :name="$name"
            :placeholder="$placeholder"
            :range="$range"
            :separate="$separate"
            :labelStart="$labelStart"
            :labelEnd="$labelEnd"
            :iconPrepend="$iconPrepend"
            :iconAppend="$iconAppend"
            :iconPrependStart="$iconPrependStart"
            :iconAppendStart="$iconAppendStart"
            :iconPrependEnd="$iconPrependEnd"
            :iconAppendEnd="$iconAppendEnd"
            {{ $attributes }}
        />

        {{-- 2. Hidden Input --}}
        <x-bs::input type="hidden" name="{{ $name }}" value="JSON.stringify(value)" />

        {{-- 3. Dialog Popup --}}
        <x-bs::date-picker.dialog />
    </div>
</x-bs::input.wrapper>
