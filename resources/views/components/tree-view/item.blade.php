@props([
    'label',
    'icon' => null,     // Standard null, damit wir wissen, ob der User was übergeben hat
    'open' => false,
    'active' => false,
    'size' => null,
    'url' => null,
    'variant' => null,  // Auch null, damit wir Defaults setzen können
    'isFolder' => null,
])

@php
    use Nexus633\BootstrapUi\Facades\TreeView;

    // 1. Status ermitteln (Ist es ein Ordner?)
    // Entweder explizit übergeben oder via Slot-Prüfung
    $hasChildren = $isFolder ?? $slot->isNotEmpty();

    // 2. Icon Logik
    // A) Icon für geschlossenen Zustand / Datei
    if ($icon) {
        // User hat manuell ein Icon gesetzt (z.B. icon="hdd")
        $iconClosed = $icon;
    } else {
        // Automatisch via Service basierend auf Label (Endung) oder Ordner-Status
        $iconClosed = TreeView::getIcon($label, $hasChildren);
    }

    // B) Icon für offenen Zustand
    if ($hasChildren) {
        // Bei Ordnern fragen wir IMMER den Service nach dem "Open"-Icon (z.B. folder2-open)
        // unabhängig davon, was manuell als closed icon gesetzt wurde (meistens will man folder/folder-open Logik)
        $iconOpen = TreeView::getIcon($label, true, true);
    } else {
        // Bei Dateien ändert sich das Icon nicht
        $iconOpen = $iconClosed;
    }

    // 3. Farblogik (Variant)
    if ($active) {
        $colorClass = 'text-primary';
    } elseif ($variant) {
        $colorClass = 'text-' . $variant; // Manuelle Farbe
    } else {
        // Standard: Ordner = Warning (Gelb), Datei = Secondary (Grau)
        $colorClass = $hasChildren ? 'text-warning' : 'text-secondary';
    }

    // 4. Alpine Data
    $alpineData = "{ expanded: " . ($open ? 'true' : 'false') . " }";
@endphp

<li class="tree-view-item my-1" x-data="{{ $alpineData }}">

    <div @class([
            'tree-view-item-row d-flex align-items-center justify-content-between position-relative',
            'active' => $active,
            'cursor-pointer' => $hasChildren
         ])
         @if($hasChildren) @click="expanded = !expanded" @endif
    >
        {{-- Linke Seite: Icon & Text --}}
        <div class="d-flex align-items-center overflow-hidden">
            <span class="me-2 {{ $colorClass }}">
                @if($hasChildren)
                    {{-- Toggle Logik für Ordner --}}
                    <span x-show="!expanded">
                        <x-bs::icon :name="$iconClosed" size="5" />
                    </span>
                    <span x-show="expanded" style="display: none;">
                        <x-bs::icon :name="$iconOpen" size="5" />
                    </span>
                @else
                    {{-- Statisches Icon für Datei --}}
                    <x-bs::icon :name="$iconClosed" size="5" />
                @endif
            </span>

            <div class="text-truncate {{ $active ? 'fw-bold text-primary' : 'text-body' }}">
                @if($url && !$hasChildren)
                    {{-- stretched-link für Klickbarkeit der ganzen Zeile (außer Buttons darüber) --}}
                    <a href="{{ $url }}" class="text-decoration-none text-reset stretched-link">
                        {{ $label }}
                    </a>
                @else
                    {{ $label }}
                @endif
            </div>
        </div>

        {{-- Rechte Seite: Badges & Aktionen --}}
        <div class="d-flex align-items-center gap-2 ms-2">
            @if($size)
                <x-bs::badge variant="secondary" class="fw-normal bg-body-secondary text-body-secondary border border-0">
                    {{ $size }}
                </x-bs::badge>
            @endif

            @if($url && !$hasChildren)
                {{-- z-index 2 damit er über dem stretched-link liegt --}}
                <a href="{{ $url }}"
                   download
                   class="btn btn-sm btn-link p-0 text-secondary position-relative z-2"
                   title="Download">
                    <x-bs::icon name="download" />
                </a>
            @endif
        </div>
    </div>

    {{-- Rekursiver Slot --}}
    @if($hasChildren)
        <ul class="tree-view-sublist" x-show="expanded" x-collapse style="display: none;">
            {{ $slot }}
        </ul>
    @endif
</li>
