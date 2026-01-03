@props([
    'flush' => false,
    'numbered' => false,
    'horizontal' => false,
    'tag' => null, // Manuelles Override bleibt möglich
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();

    $field->addClass('list-group')
          ->addClassWhen($flush, 'list-group-flush')
          ->addClassWhen($numbered, 'list-group-numbered')
          ->addClassWhen($horizontal, 'list-group-horizontal');

    // --- AUTOMATISCHE TAG-ERKENNUNG ---
    if ($tag) {
        $actualTag = $tag;
    }
    elseif ($numbered) {
        $actualTag = 'ol';
    }
    else {
        $slotContent = (string) $slot;
        if (str_contains($slotContent, '<a ') || str_contains($slotContent, '<button ')) {
            $actualTag = 'div';
        } else {
            $actualTag = 'ul';
        }
    }
@endphp

<{{ $actualTag }} {{ $attributes->class($field->getClasses()) }}>
{{ $slot }}
</{{ $actualTag }}>
