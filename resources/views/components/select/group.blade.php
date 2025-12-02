@props([
    'label' => ''
])

<optgroup label="{{ $label }}" {{ $attributes }}>
    {{ $slot }}
</optgroup>
