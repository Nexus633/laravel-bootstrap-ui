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
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $iconClass = Icon::toClass($icon);

    if ($href) {
        $tag = 'a';
    } elseif ($attributes->has('type') || $attributes->has('wire:click') || $action) {
        $tag = 'button';
        $action = true;
    } else {
        $tag = 'li';
    }

    $field = BootstrapUi::make();
    $field->addClass('list-group-item')
          ->addClassWhen(($tag === 'a' || $tag === 'button') || $action, 'list-group-item-action')
          ->addClassWhen($active, 'active')
          ->addClassWhen($disabled, 'disabled')
          ->addClassWhen($variant, 'list-group-item-' . $variant)
          ->addClassWhen($badge, ['d-flex', 'justify-content-between', 'align-items-center'])
          ->addDataWhen($href, 'href', $href)
          ->addDataWhen($disabled && $tag === 'button', 'disabled', 'disabled')
          ->addDataWhen($disabled && $tag === 'a', 'aria-disabled', 'true')
          ->addDataWhen($disabled && $tag === 'a', 'tabindex', '-1')
          ->addDataWhen($active, 'aria-current', 'true')
          ;
@endphp

<{{ $tag }} {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
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
