@props([
    'name',
    'label' => null,
    'rows' => 3,
    'hint' => null,
    'simple' => false,
    'resize' => 'vertical',
])

@php
    $id = $attributes->get('id') ?? 'textarea-' . uniqid();
    $error = $errors->first($name);
@endphp

@if($simple)
    {{-- Simple Mode (für Input Groups) --}}
    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows }}"
        style="resize: {{ $resize }}"
        {{ $attributes->class([
            'form-control',
            'is-invalid' => $errors->has($name),
        ]) }}
    >{{ $slot }}</textarea>

@else
    {{-- Standard Mode (mit Label & Wrapper) --}}
    <div class="mb-3">
        @if($label)
            <label for="{{ $id }}" class="form-label">{{ $label }}</label>
        @endif

        <textarea
            name="{{ $name }}"
            id="{{ $id }}"
            rows="{{ $rows }}"
            style="resize: {{ $resize }}"
            {{ $attributes->class([
                'form-control',
                'is-invalid' => $errors->has($name),
            ]) }}
        >{{ $slot }}</textarea>

        @if($hint)
            <div class="form-text">{{ $hint }}</div>
        @endif

        @if($error)
            <div class="invalid-feedback">{{ $error }}</div>
        @endif
    </div>
@endif
