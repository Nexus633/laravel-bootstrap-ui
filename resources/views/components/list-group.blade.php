@props([
    'flush' => false,
    'numbered' => false,
    'horizontal' => false,
    'tag' => null, // Manuelles Override bleibt möglich
])

@php
    $classes = ['list-group'];

    if ($flush) $classes[] = 'list-group-flush';
    if ($numbered) $classes[] = 'list-group-numbered';
    if ($horizontal) $classes[] = 'list-group-horizontal';

    // --- AUTOMATISCHE TAG-ERKENNUNG ---

    // 1. Wenn der User explizit was setzt, nimm das.
    if ($tag) {
        $actualTag = $tag;
    }
    // 2. Wenn es nummeriert ist, MUSS es <ol> sein.
    elseif ($numbered) {
        $actualTag = 'ol';
    }
    // 3. Jetzt die Magie: Wir scannen den Inhalt.
    else {
        // Wir wandeln den Slot in einen String um.
        // Das enthält das fertig gerenderte HTML der Kinder.
        $slotContent = (string) $slot;

        // Prüfen, ob Ankertags (<a ...>) oder Buttons (<button ...>) vorkommen
        if (str_contains($slotContent, '<a ') || str_contains($slotContent, '<button ')) {
            $actualTag = 'div';
        } else {
            $actualTag = 'ul';
        }
    }
@endphp

<{{ $actualTag }} {{ $attributes->class($classes) }}>
{{ $slot }}
</{{ $actualTag }}>
