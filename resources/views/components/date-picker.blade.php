@props([
    'name' => null,
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
    'icon' => 'calendar-event',
    'double' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    
    $field = BootstrapUi::make($name);
    $id = $attributes->getOrCreateId('datepicker-');
    $hasError = $field->hasError();

    $wireModel = $attributes->wire('model');
    $pickerId = 'dp-' . md5(($name ?? 'dp') . $id);

    $entangleStr = $wireModel->value() ? "\$wire.entangle('{$wireModel->value()}').live" : 'null';
    $mode = $range ? 'range' : 'single';
    $placeholder = $placeholder ?? ($range ? __('bs::bootstrap-ui.date-picker.inputs.from_to') : __('bs::bootstrap-ui.date-picker.inputs.select_date'));

    $disableJson = json_encode($disable);
    $transMonths = __('bs::bootstrap-ui.date-picker.months');
    $monthsJson = json_encode(is_array($transMonths) ? $transMonths : ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez']);
    $transDays = __('bs::bootstrap-ui.date-picker.days');
    $daysJson = json_encode(is_array($transDays) ? $transDays : ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So']);

    $iconPrepend = $attributes->pluck('icon:prepend', $icon);
    if ($iconPrepend === 'false' || $iconPrepend === false) $iconPrepend = null;
    $iconAppend = $attributes->pluck('icon:append');
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

        @if($name)
            <x-bs::input type="hidden" :name="$name" x-bind:value="JSON.stringify(value)" />
        @endif

        <x-bs::date-picker.dialog />
    </div>
</x-bs::input.wrapper>
