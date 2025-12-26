@props([
    'variant' => 'primary', // success, danger, warning, info
    'dismissible' => false,
    'icon' => null,
    'iconFs' => 4
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

    $iconFs = $attributes->get('icon:fs', $iconFs);
    $attributes = $attributes->except(['icon:fs']);

    //if($iconFs) $iconFs = 'fs-' . $iconFs;
    // Alias Korrektur: Falls jemand 'error' statt 'danger' schreibt
    $realVariant = $variant === 'error' ? 'danger' : $variant;

    $classes = 'alert alert-' . $realVariant . ' d-flex align-items-center';
    
    if ($dismissible) {
        $classes .= ' alert-dismissible fade show';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes, 'role' => 'alert']) }}>

    <x-bs::icon name="{{ $icon }}" class="flex-shrink-0 me-2" size="{{ $iconFs }}" />

    {{-- Content Wrapper --}}
    <div>
        {{ $slot }}
    </div>

    {{-- Close Button --}}
    @if($dismissible)
        <x-bs::button.close dismiss="alert" />
    @endif
</div>
