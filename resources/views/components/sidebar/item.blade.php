@props([
    'href' => '#',
    'activeVariant' => 'body-secondary',
    'name' => null,
    'icon' => null,
    'badge' => null,
    'badgeVariant' => 'danger',
    'exact' => false
])

@php
    // CustomAttributs
    $badgeVariant = $attributes->get('badge:variant') ?? $badgeVariant;
    $activeVariant = $attributes->get('active:variant') ?? $activeVariant;

    $attributes = $attributes->except(['badge:variant', 'active:variant']);

    // 1. URL Normalisieren
    $path = ltrim($href, '/');

    // 2. Active Check
    // Wenn exact=true (z.B. Dashboard), muss es genau matchen.
    // Sonst matcht es auch Unterseiten (z.B. /users/create matcht /users).
    $isActive = $exact ? request()->is($path) : request()->is($path . '*');

    // 3. Styling
    // Wir nutzen eine einfache Logik:
    // Aktiv = wird vom prop activeVariant oder dem Attribut active:variant bestimmt. Standard: body-secondary
    // Inaktiv = Transparenter Hintergrund + Graue Schrift.

    $commonClasses = "nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 transition-base";

    if ($isActive) {
        // === AKTIV ===
        $classes = $commonClasses . ' bg-'. $activeVariant .' text-white shadow-sm fw-medium';
        $iconClass = 'text-white'; // Icon muss auch weiß sein
    } else {
        // === INAKTIV ===
        // Grau, beim Hover wird es leicht heller (body-secondary bg)
        $classes = $commonClasses . ' text-body-secondary hover-bg-body-secondary';
        $iconClass = 'text-body-tertiary'; // Icon etwas dunkler
    }
@endphp

<li class="nav-item">
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>

        @if($icon)
            <x-bs::icon :name="$icon" size="1.1rem" class="{{ $iconClass }}" />
        @endif

        <span class="flex-grow-1 text-truncate" style="font-size: 0.95rem;">
            @isset($name)
                {{ $name }}
            @else
                {{ $slot }}
            @endisset
        </span>

        @if($badge)
            <x-bs::badge :variant="$badgeVariant" >
                {{ $badge }}
            </x-bs::badge>
        @endif
    </a>
</li>
