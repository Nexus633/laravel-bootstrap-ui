@props([
    'variant' => 'primary',
    'size' => null,
    'type' => 'button',
    'loading' => null,
    'icon' => null,
    'outline' => false,
    'dismiss' => null,
    'relative' => false,
    'align' => null,
    'collapse' => null,
    'offcanvas' => null, // NEU: ID des Offcanvas, z.B. 'sidebarCart'
    'pagination' => false
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    $id = $attributes->get('id') ?? 'btn-' . uniqid();
    $cleanIcon = Icon::toClass($icon);

    $classes = 'btn';

    // ... (Hier die unveränderte Logik für Variant, Size, Relative, Align) ...
    $actualVariant = $outline && !str_contains($variant, 'outline') ? 'outline-' . $variant : $variant;
    $classes .= ' btn-' . $actualVariant;
    if ($size) $classes .= ' btn-' . $size;
    if ($relative) $classes .= ' position-relative';

    if ($align === 'right') $classes .= ' float-end';
    elseif ($align === 'block') $classes .= ' w-100 d-block';
    elseif ($align === 'center') $classes .= ' d-block mx-auto';

    $isLink = $attributes->has('href');
    $tag = $isLink ? 'a' : 'button';

    // Helper für Toggle Attribute
    $extraAttrs = [];

    // CASE A: COLLAPSE
    if ($collapse) {
        $extraAttrs['data-bs-toggle'] = 'collapse';
        $target = (str_starts_with($collapse, '.') || str_starts_with($collapse, '#')) ? $collapse : '#' . $collapse;
        $extraAttrs['data-bs-target'] = $target;
        $extraAttrs['aria-expanded'] = 'false';
        $extraAttrs['aria-controls'] = trim($target, '#.');
        if ($isLink && !$attributes->has('href')) $extraAttrs['href'] = $target;
    }

    // CASE B: OFFCANVAS (NEU)
    if ($offcanvas) {
        $extraAttrs['data-bs-toggle'] = 'offcanvas';
        // Bei Offcanvas ist es immer eine ID, Klassen funktionieren hier technisch nicht gut als Target
        $target = str_starts_with($offcanvas, '#') ? $offcanvas : '#' . $offcanvas;
        $extraAttrs['data-bs-target'] = $target;
        $extraAttrs['aria-controls'] = trim($target, '#');
        if ($isLink && !$attributes->has('href')) $extraAttrs['href'] = $target;
    }

    if($pagination){
        $classes = "page-link";
    }

@endphp

<{{ $tag }} @if($id) id="{{ $id }}" @endif
@if(!$isLink) type="{{ $type }}" @endif
{{ $attributes->merge($extraAttrs)->merge(['class' => $classes]) }}
@if($loading && !$isLink)
    wire:loading.attr="disabled" wire:target="{{ $loading }}"
@endif
@if($dismiss)
    data-bs-dismiss="{{ $dismiss }}" aria-label="Close"
@endif
>
@if($loading)
    <x-bs::spinner type="border" size="sm" label="Loading..."/>
@endif

@if($cleanIcon && !$loading)
    <x-bs::icon :name="$cleanIcon" class="me-1" />
@endif

{{ $slot }}
</{{ $tag }}>
