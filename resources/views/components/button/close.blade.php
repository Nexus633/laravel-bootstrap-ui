@props([
    'dismiss' => null,  // Pflichtfeld für Service-Steuerung
    'target' => null,
    'variant' => null,
    'outline' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Illuminate\Support\Str;

    $field = BootstrapUi::make();

    $variantClass = '';
    if($variant){
        $actualVariant = $outline && !str_contains($variant, 'outline') ? 'outline-' . $variant : $variant;
        $variantClass = ' btn-' . $actualVariant;
    }

    $field->addClassWhen(empty(Str::trim($slot)), 'btn-close')
          ->addClassWhen(!empty(Str::trim($slot)), 'btn')
          ->addClass($variantClass)
          ->addDataWhen($dismiss, 'data-bs-dismiss', $dismiss)
          ->addDataWhen($target, 'data-bs-target', $target);
@endphp

<button
    type="button"
    {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}
    aria-label="Close"
    @if($dismiss === 'modal') @click="$el.blur()" @endif
>
    {{ $slot }}
</button>
