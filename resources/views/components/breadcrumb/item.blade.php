@props([
    'href' => null,
    'active' => false,
    'icon' => null,
    'title' => null // Optional, falls man nicht den Slot nutzen will
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    // Wenn kein Link da ist, gehen wir davon aus, dass es die aktive Seite ist
    $isActive = $active || is_null($href);
    
    $iconClass = Icon::toClass($icon);

    // Array für die Klassen-Logik
    $classes = [
        'breadcrumb-item',
        'active' => $isActive
    ];
@endphp

<li {{ $attributes->class($classes) }} @if($isActive) aria-current="page" @endif>
    @if($isActive)
        {{-- Aktive Seite (Nur Text/Icon) --}}
        @if($iconClass) <i class="{{ $iconClass }}"></i> @endif
        {{ $title ?? $slot }}
    @else
        {{-- Link --}}
        <a href="{{ $href }}" class="text-decoration-none">
            @if($iconClass) <i class="{{ $iconClass }}"></i> @endif
            {{ $title ?? $slot }}
        </a>
    @endif
</li>
