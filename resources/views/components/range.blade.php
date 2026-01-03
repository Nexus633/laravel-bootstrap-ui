@props([
    'name' => null,
    'label' => null,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'hint' => null,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make($name);

    $id = $attributes->getOrCreateId('range-');
    $hasError = $field->hasError();

    $field->addClass('form-range')
          ->addClassWhen($hasError, 'is-invalid');
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">
    <input
        type="range"
        @if($name) name="{{ $name }}" @endif
        id="{{ $id }}"
        min="{{ $min }}"
        max="{{ $max }}"
        step="{{ $step }}"
        {{ $attributes->class($field->getClasses()) }}
    />
</x-bs::input.wrapper>
