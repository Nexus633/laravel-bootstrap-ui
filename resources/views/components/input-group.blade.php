@props([
    'label' => null, // Optionales Label über der ganzen Gruppe
    'size' => null,  // sm, lg
    'name' => null,  // Für Error-Handling der gesamten Gruppe
])

@php
    $classes = 'input-group';
    if ($size) $classes .= ' input-group-' . $size;
    // Falls ein Input in der Gruppe einen Fehler hat, kann man die Klasse hier setzen, 
    // aber meistens setzt Bootstrap .is-invalid direkt am Input.
    $hasError = $name && $errors->has($name);
    if ($hasError) $classes .= ' has-validation'; // Bootstrap Validation Fix
@endphp

<div class="mb-3">
    @if($label)
        <label class="form-label">{{ $label }}</label>
    @endif

    <div {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </div>

    @if($hasError)
        <div class="invalid-feedback d-block">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
