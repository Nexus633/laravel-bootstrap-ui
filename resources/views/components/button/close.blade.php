@props([
    'dismiss' => null,  // Pflichtfeld für Service-Steuerung
    'variant' => null,
    'outline' => false,
])

@php
    use Illuminate\Support\Str;
    if($dismiss) $attributes['data-bs-dismiss'] = $dismiss;
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

<button type="button" {{ $attributes->class($classes) }} aria-label="Close" {{ $attributes }}>
    {{ $slot }}
</button>
