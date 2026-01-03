@props([
    'id' => null,
    'flush' => false,       // true = Entfernt Rahmen (z.B. für Card-Integration)
    'alwaysOpen' => false,  // true = Items schließen sich nicht gegenseitig
    'persist' => false,     // true = LocalStorage, false = SessionStorage
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();

    $accordionId = $attributes->getOrCreateId('accordion-');

    $field->addClass('accordion')
            ->addClassWhen($flush, 'accordion-flush');
@endphp

{{-- 
    Wir rendern das Div mit den Klassen. 
    WICHTIG: Die ID muss vom User übergeben werden (z.B. id="myFaq"), 
    damit @aware im Kind darauf zugreifen kann.
--}}
<div
    id="{{ $accordionId }}"
    {{ $attributes->class($field->getClasses()) }}
    x-data="bsUiState('{{ $accordionId }}', 'accordion', {{ $persist ? 'true' : 'false' }})"
    {{ $attributes }}
>
    {{ $slot }}
</div>
