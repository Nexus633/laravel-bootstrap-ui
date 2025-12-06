@props([
    'flush' => false,      // true = Entfernt Rahmen (z.B. für Card-Integration)
    'alwaysOpen' => false, // true = Items schließen sich nicht gegenseitig
])

@php
    $id = $attributes->get('id') ?? 'accordion-' . uniqid();

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
<div {{ $attributes->class($classes)  }}>
    {{ $slot }}
</div>
