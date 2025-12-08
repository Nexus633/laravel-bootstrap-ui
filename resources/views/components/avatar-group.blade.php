<div {{ $attributes->class(['d-flex']) }}>
    {{-- 
        Wir nutzen CSS '>' Selektor Logik inline oder global.
        Hier ein kleiner Trick: Wir geben den Kindern negative Margins via Klasse im Parent nicht direkt möglich.
        Daher nutzen wir eine Hilfsklasse oder Inline-Styles im Slot?
        
        Sauberste Lösung: Ein kleines Inline-Style für den Container, 
        der die Kinder beeinflusst.
    --}}
    <style>
        .avatar-group > * + * {
            margin-left: -0.8rem; /* Überlappung */
        }
        .avatar-group > *:hover {
            z-index: 10;
            position: relative;
        }
    </style>

    <div class="avatar-group d-flex align-items-center">
        {{ $slot }}
    </div>
</div>
