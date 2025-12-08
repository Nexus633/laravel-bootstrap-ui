@props([
    'height' => null, // z.B. '20px' oder '1px'
])

<div {{ $attributes->class(['progress']) }}
     @if($height) style="height: {{ $height }}" @endif
        {{ $attributes }}
>
    {{ $slot }}
</div>
