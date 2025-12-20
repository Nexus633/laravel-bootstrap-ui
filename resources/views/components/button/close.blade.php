@props([
    'dismiss' => null,  // Pflichtfeld für Service-Steuerung
    'target' => null,
    'variant' => null,
    'outline' => false,
])

@php
    use Illuminate\Support\Str;
    if($dismiss) $attributes['data-bs-dismiss'] = $dismiss;
    if($target) $attributes['data-bs-target'] = $target;
    $variantClass = '';
    if($variant){
        $actualVariant = $outline && !str_contains($variant, 'outline') ? 'outline-' . $variant : $variant;
        $variantClass = ' btn-' . $actualVariant;
    }

    $classes = [
        'btn-close' => empty(Str::trim($slot)),
        'btn' => !empty(Str::trim($slot)),
        $variantClass
    ];

@endphp

<button
    type="button"
    {{ $attributes->class($classes) }}
    aria-label="Close"
    @if($dismiss === 'modal') @click="$el.blur()" @endif
    {{ $attributes }}>
    {{ $slot }}
</button>
