@props([
    'title',
    'icon' => null,
    'expanded' => false,
    'variant' => null // NEU: z.B. 'danger', 'success', 'primary'
])

@aware([
    'id' => null,
    'alwaysOpen' => false,
    'variant' => null
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    // IDs generieren
    $uniqueId = uniqid('acc-item-');
    $headerId = 'heading-' . $uniqueId;
    $collapseId = 'collapse-' . $uniqueId;

    $iconClass = Icon::toClass($icon);

    // Parent ID holen
    $parentId = $id;

    // --- KLASSEN LOGIK ---

    // Wrapper Klassen
    $itemClasses = ['accordion-item'];

    // Wenn eine Variante gesetzt ist, färben wir oft auch den Rand passend
    if ($variant) {
        $itemClasses[] = 'border-' . $variant;
    }

    // Button Klassen
    $buttonClasses = ['accordion-button'];

    if (!$expanded) {
        $buttonClasses[] = 'collapsed';
    }

    // Farbe hinzufügen (text-bg-primary sorgt für Hintergrund UND Kontrast-Schriftfarbe)
    if ($variant) {
        $buttonClasses[] = 'text-bg-' . $variant;
    }
@endphp

<div {{ $attributes->class($itemClasses) }}>
    <h2 class="accordion-header" id="{{ $headerId }}">
        <button
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#{{ $collapseId }}"
                aria-expanded="{{ $expanded ? 'true' : 'false' }}"
                aria-controls="{{ $collapseId }}"
                {{-- Hier übergeben wir das Button-Klassen Array --}}
                class="{{ implode(' ', $buttonClasses) }}"
        >
            @if($iconClass)
                <i class="{{ $iconClass }} me-2"></i>
            @endif
            {{ $title }}
        </button>
    </h2>
    <div
            id="{{ $collapseId }}"
            class="accordion-collapse collapse {{ $expanded ? 'show' : '' }}"
            aria-labelledby="{{ $headerId }}"

            @if(!$alwaysOpen && $parentId)
                data-bs-parent="#{{ $parentId }}"
            @endif
    >
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>
