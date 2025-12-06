@props([
    'variant' => 'primary', // success, danger, warning, info
    'dismissible' => false,
    'icon' => null,
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;
    // Automatische Icon-Wahl basierend auf Variante (Bootstrap Icons)
    if (!$icon) {
        $icon = match($variant) {
            'success' => 'bi bi-check-circle-fill',
            'danger', 'error' => 'bi bi-exclamation-triangle-fill', // 'error' als Alias für danger
            'warning' => 'bi bi-exclamation-circle-fill',
            'info' => 'bi bi-info-circle-fill',
            default => 'bi bi-info-circle',
        };
    }else{
        $icon = Icon::toClass($icon);
    }
    
    // Alias Korrektur: Falls jemand 'error' statt 'danger' schreibt
    $realVariant = $variant === 'error' ? 'danger' : $variant;

    $classes = 'alert alert-' . $realVariant . ' d-flex align-items-center';
    
    if ($dismissible) {
        $classes .= ' alert-dismissible fade show';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes, 'role' => 'alert']) }}>

    {{-- Icon --}}
    <i class="{{ $icon }} flex-shrink-0 me-2 fs-4"></i>

    {{-- Content Wrapper --}}
    <div>
        {{ $slot }}
    </div>

    {{-- Close Button --}}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
