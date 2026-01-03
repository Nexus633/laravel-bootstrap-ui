@props([
    'name' => null,
    'label' => null,
    'rows' => 3,
    'hint' => null,
    'simple' => false,
    'resize' => 'vertical',
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make($name);

    $isFloating = $attributes->pluck('label:floating');
    $id = $attributes->getOrCreateId('textarea-');
    $hasError = $field->hasError();

    $field->addClass('form-control')
          ->addClassWhen($hasError, 'is-invalid')
          ->addStyleWhen($resize, 'resize', $resize)
          ->addStyleWhen(!$simple, 'height', 'calc(2.5rem + ' . ($rows * 1.5) . 'rem)');

@endphp

@if($simple)
    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows }}"
        {{ $attributes->class($field->getClasses())->merge(['style' => $field->getStyles()]) }}
    >{{ $slot }}</textarea>
@else
    <x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint" :floating="$isFloating">
        @if($isFloating)
            <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                <textarea
                    name="{{ $name }}"
                    id="{{ $id }}"
                    placeholder="{{ $label }}"
                    {{ $attributes->class($field->getClasses())->merge(['style' => $field->getStyles()]) }}
                >{{ $slot }}</textarea>
                <x-bs::text label for="{{ $id }}">{{ $label }}</x-bs::text>
            </div>
        @else
            <textarea
                name="{{ $name }}"
                id="{{ $id }}"
                rows="{{ $rows }}"
                {{ $attributes->class($field->getClasses()) }}
            >{{ $slot }}</textarea>
        @endif
    </x-bs::input.wrapper>
@endif
