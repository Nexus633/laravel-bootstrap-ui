@props([
    'icon' => null,
    'href' => null,
    'active' => false,
    'disabled' => false,
    'danger' => false, // Für "Löschen" Aktionen rot färben
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;
    $classes = 'dropdown-item d-flex align-items-center';
    if ($active) $classes .= ' active';
    if ($disabled) $classes .= ' disabled';
    if ($danger) $classes .= ' text-danger';
    $icon = Icon::toClass($icon);
    // Tag bestimmen: Link oder Button?
    $tag = $href ? 'a' : 'button';
@endphp

<li>
    <{{ $tag }}
        @if($href) href="{{ $href }}" @else type="button" @endif
    {{ $attributes->merge(['class' => $classes]) }}
    >
    @if($icon)
        <i class="{{ $icon }} me-2 opacity-75"></i>
    @endif

    <span>{{ $slot }}</span>
</{{ $tag }}>
</li>
