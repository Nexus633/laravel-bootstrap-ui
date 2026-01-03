@props([
    'name' => null,      // Optional: Manuelle ID
    'title' => '',       // Der Titel (wird zum Slug, falls name fehlt)
    'icon' => null,
    'expanded' => false,
    'variant' => null,
    'borderVariant' => null
])

@aware([
    'id' => null,
    'alwaysOpen' => false,
    'variant' => null,
    'borderVariant' => null
])

@php
    use Illuminate\Support\Str;
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $item = BootstrapUi::make($name);
    $button = BootstrapUi::make($name);
    // 1. Parent ID sicherstellen (Fallback nötig, falls Parent keine ID hat)
    $parentId = $attributes->getOrCreateId('accordion-');

    // 2. Suffix bestimmen (Die Magie)
    // Prio 1: Explizites 'name' Attribut
    // Prio 2: Slug aus dem 'title' (z.B. "Meine Einstellungen" -> "meine-einstellungen")
    // Prio 3: Fallback (uniqid), falls beides leer ist (vermeidet Fehler, bricht aber State)
    if ($name) {
        $suffix = $name;
    } elseif (!empty($title)) {
        // Str::slug macht alles klein und ersetzt Leerzeichen durch Bindestriche
        $suffix = Str::slug($title);
    } else {
        $suffix = uniqid('item-');
    }

    // 3. Deterministische IDs bauen
    // Ergebnis z.B.: "settingsAccordion-collapse-allgemeine-infos"
    $headerId   = $parentId . '-heading-' . $suffix;
    $collapseId = $parentId . '-collapse-' . $suffix;

    $icon = Icon::toClass($icon);

    // --- KLASSEN LOGIK ---
    $item->addClass('accordion-item')
         ->addClassWhen($borderVariant, 'border-' . $variant);

    $button->addClass('accordion-button')
         ->addClassWhen(!$expanded, 'collapsed')
         ->addClassWhen($variant, 'text-bg-' . $variant);
@endphp

<div {{ $attributes->class($item->getClasses()) }}>
    <h2 class="accordion-header" id="{{ $headerId }}">
        <button
            class="{{ $button->getClasses() }}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $collapseId }}"
            aria-expanded="{{ $expanded ? 'true' : 'false' }}"
            aria-controls="{{ $collapseId }}"
        >
            @if($icon)
                <x-bs::icon :name="$icon" class="me-2" />
            @endif
            {{ $title }}
        </button>
    </h2>
    <div
        id="{{ $collapseId }}"
        class="accordion-collapse collapse {{ $expanded ? 'show' : '' }}"
        aria-labelledby="{{ $headerId }}"
        {{-- Nur data-bs-parent setzen, wenn NICHT alwaysOpen --}}
        @if(!$alwaysOpen) data-bs-parent="#{{ $parentId }}" @endif
    >
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>
