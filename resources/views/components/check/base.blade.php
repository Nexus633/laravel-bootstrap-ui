@props([
    'name' => null,
    'label' => null,
    'type' => 'checkbox',
    'value' => 1,
    'checked' => false,
    'inline' => false,
    'switch' => false,
    'hint' => null,
    'id' => null,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make($name);

    $id = $attributes->getOrCreateId('check-');
    $hasError = $field->hasError();

    $field->addClass('form-check')
          ->addClassWhen($switch, 'form-switch')
          ->addClassWhen($inline, 'form-check-inline')
          ->addClassWhen(!$inline, 'mb-3')
          ->addClassWhen($inline, ['d-inline-flex', 'align-items-center']);

    // Input Klassen werden jetzt mit dem Helper gebaut
    $inputField = BootstrapUi::make(null); // Eine separate Instanz nur für die Input-Klassen
    $inputField->addClass('form-check-input')
               ->addClassWhen($hasError, 'is-invalid')
               ->addDataWhen($switch, 'role', 'switch')
               ->addDataWhen($checked, 'checked', 'checked')
               ->addDataWhen($name, 'name', $name)
               ;
@endphp

<div class="{{ $field->getClasses() }}">
    <input
        type="{{ $type }}"
        id="{{ $id }}"
        value="{{ $value }}"
        {{ $attributes->class($inputField->getClasses())->merge($inputField->getDataAttributes()) }}
    />

    @if($label)
        <x-bs::text label for="{{ $id }}" class="form-check-label">
            {{ $label }}
        </x-bs::text>
    @endif

    @if($hasError)
        <x-bs::text div class="invalid-feedback">{{ $errors->first($name) }}</x-bs::text>
    @elseif($hint)
        <x-bs::text div class="form-text">{{ $hint }}</x-bs::text>
    @endif
</div>
