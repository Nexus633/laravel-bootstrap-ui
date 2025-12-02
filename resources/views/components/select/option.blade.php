@props([
    'value' => null,
    'content' => null
])

@php
    // Falls kein Content übergeben wurde, nehmen wir den Value als Text
    $content = $content ?? $value;
@endphp

<option value="{{ $value }}" {{ $attributes }}>{{ $content }}</option>
