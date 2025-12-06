@props([
    'content' => null
])
@php

    $baseClasses = [
        "card-text"
    ];

@endphp
<p class="{{ $attributes->class($baseClasses) }}">@if($content) {{ $content }} @else {{ $slot }} @endif</p>
