@props([
    'active' => false,
    'disabled' => false,
    'href' => null,        // Macht es zum Link (<a>)
    'variant' => null,     // Farbe: primary, danger, etc.
    'action' => false,     // Erzwingt Hover-Effekt (bei Links/Buttons autom. an)
    'icon' => null,        // Icon am Anfang
    'badge' => null,       // Badge am Ende (String oder Zahl)
    'badgeVariant' => 'primary', // Farbe des Badges
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;
    $iconClass = Icon::toClass($icon);

    // Tag-Entscheidung
    if ($href) {
        $tag = 'a';
    } elseif ($attributes->has('type') || $attributes->has('wire:click') || $action) {
        // Wenn es wie ein Button agiert
        $tag = 'button';
        $action = true; // Buttons sind immer "Actionable"
    } else {
        $tag = 'li';
    }
    
    // Klassen
    $classes = ['list-group-item'];
    
    // Bei Links/Buttons brauchen wir 'list-group-item-action' für den Hover
    if (($tag === 'a' || $tag === 'button') || $action) {
        $classes[] = 'list-group-item-action';
    }

    if ($active) $classes[] = 'active';
    if ($disabled) $classes[] = 'disabled';
    if ($variant) $classes[] = 'list-group-item-' . $variant;

    // Flexbox für Badge-Support (Text links, Badge rechts)
    if ($badge) {
        $classes[] = 'd-flex justify-content-between align-items-center';
    }
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
@if($disabled && $tag === 'button') disabled @endif
@if($disabled && $tag === 'a') aria-disabled="true" tabindex="-1" @endif
@if($active) aria-current="true" @endif
{{ $attributes->class($classes) }}
>
{{-- Inhalt Wrapper (nötig falls Flexbox durch Badge aktiv ist, damit Text zusammenbleibt) --}}
@if($badge)
    <div>
        @if($iconClass) <i class="{{ $iconClass }} me-2"></i> @endif
        {{ $slot }}
    </div>
    <span class="badge text-bg-{{ $badgeVariant }} rounded-pill">{{ $badge }}</span>
@else
    @if($iconClass) <i class="{{ $iconClass }} me-2"></i> @endif
    {{ $slot }}
@endif
</{{ $tag }}>
