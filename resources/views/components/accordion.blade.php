@props([
    'id' => null,
    'flush' => false,       // true = Entfernt Rahmen (z.B. für Card-Integration)
    'alwaysOpen' => false,  // true = Items schließen sich nicht gegenseitig
    'persist' => false,     // true = LocalStorage, false = SessionStorage
])

@php
    $accordionId = $id ?? 'accordion-' . uniqid();

     // Klassen zusammenbauen
     $classes = [
         'accordion',
         'accordion-flush' => $flush,
     ];
@endphp

{{-- 
    Wir rendern das Div mit den Klassen. 
    WICHTIG: Die ID muss vom User übergeben werden (z.B. id="myFaq"), 
    damit @aware im Kind darauf zugreifen kann.
--}}
<div
    id="{{ $accordionId }}"
    {{ $attributes->class($classes) }}
    x-data="bsUiState('{{ $accordionId }}', 'accordion', {{ $persist ? 'true' : 'false' }})"
    {{ $attributes }}
>
    {{ $slot }}
</div>
