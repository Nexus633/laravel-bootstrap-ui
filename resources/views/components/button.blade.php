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
    'offcanvas' => null,
    'pagination' => false,
    'ariaLabel' => null,
    'disabled' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;

    // Validierung: Collapse und Offcanvas schließen sich aus
    if ($collapse && $offcanvas) {
        throw new InvalidArgumentException(__('bs::bootstrap-ui.button.InvalidArgumentException'));
    }

    // Icon prepend/append aus Attributes holen und entfernen
    $iconPrepend = $attributes->get('icon:prepend');
    $iconAppend = $attributes->get('icon:append');
    $attributes = $attributes->except(['icon:prepend', 'icon:append']);

    $id = $attributes->get('id');
    $cleanIcon = $icon ? Icon::toClass($icon) : null;
    $cleanIconPrepend = $iconPrepend ? Icon::toClass($iconPrepend) : null;
    $cleanIconAppend = $iconAppend ? Icon::toClass($iconAppend) : null;

    $isLink = $attributes->has('href');
    $tag = $isLink ? 'a' : 'button';

    // Klassen-Array basiert aufbauen
    $classes = [];

    if ($pagination) {
        // Bei Pagination nur page-link, keine btn-Klassen
        $classes[] = 'page-link';
        if ($disabled) $classes[] = 'disabled';
    } else {
        // Normale Button-Klassen
        $actualVariant = $outline && !str_contains($variant, 'outline')
            ? 'outline-' . $variant
            : $variant;

        $classes[] = 'btn';
        $classes[] = 'btn-' . $actualVariant;

        if ($size) $classes[] = 'btn-' . $size;
        if ($relative) $classes[] = 'position-relative';

        // Alignment
        if ($align === 'right') $classes[] = 'float-end';
        elseif ($align === 'block') $classes[] = 'w-100 d-block';
        elseif ($align === 'center') $classes[] = 'd-block mx-auto';
    }

    $classString = implode(' ', array_filter($classes));

    // Bootstrap Toggle Attribute
    $extraAttrs = [];

    if ($collapse) {
        $extraAttrs['data-bs-toggle'] = 'collapse';
        $target = (str_starts_with($collapse, '.') || str_starts_with($collapse, '#'))
            ? $collapse
            : '#' . $collapse;
        $extraAttrs['data-bs-target'] = $target;
        $extraAttrs['aria-expanded'] = 'false';
        $extraAttrs['aria-controls'] = trim($target, '#.');

        if ($isLink && !$attributes->has('href')) {
            $extraAttrs['href'] = $target;
        }
    }

    if ($offcanvas) {
        $extraAttrs['data-bs-toggle'] = 'offcanvas';
        $target = str_starts_with($offcanvas, '#') ? $offcanvas : '#' . $offcanvas;
        $extraAttrs['data-bs-target'] = $target;
        $extraAttrs['aria-controls'] = trim($target, '#');

        if ($isLink && !$attributes->has('href')) {
            $extraAttrs['href'] = $target;
        }
    }

    if ($dismiss) {
        $extraAttrs['data-bs-dismiss'] = $dismiss;
        $extraAttrs['aria-label'] = $ariaLabel ?? 'Close';
    }

    // Disabled-State
    if ($disabled) {
        if ($isLink) {
            $extraAttrs['aria-disabled'] = 'true';
            $extraAttrs['tabindex'] = '-1';
        } else {
            $extraAttrs['disabled'] = true;
        }
    }

    // Aria-Label für Toggle-Buttons ohne Text
    if (($collapse || $offcanvas) && $slot->isEmpty() && !isset($extraAttrs['aria-label'])) {
        $extraAttrs['aria-label'] = $ariaLabel ?? 'Toggle';
    }

    // Icon-Priorität: prepend/append überschreiben das alte icon-Prop
    $hasIconPrepend = $cleanIconPrepend || ($cleanIcon && !$iconAppend);
    $hasIconAppend = $cleanIconAppend || ($cleanIcon && !$iconPrepend && $iconAppend);
@endphp

<{{ $tag }}
    @if($id) id="{{ $id }}" @endif
    @if(!$isLink) type="{{ $type }}" @endif
    {{ $attributes->merge($extraAttrs)->merge(['class' => $classString]) }}
    @if($loading)
        wire:loading.attr="disabled"
        wire:loading.class="pe-none"
        wire:target="{{ $loading }}"
    @endif
>
    @if($loading)
        <span wire:loading wire:target="{{ $loading }}">
            <x-bs::spinner type="border" size="sm" label="Loading..." />
        </span>
    @endif

    @if(!$loading && $cleanIconPrepend)
        <x-bs::icon :name="$cleanIconPrepend" class="me-1" />
    @elseif(!$loading && $cleanIcon && !$iconAppend)
        <x-bs::icon :name="$cleanIcon" class="me-1" />
    @endif

    {{ $slot }}

    @if(!$loading && $cleanIconAppend)
        <x-bs::icon :name="$cleanIconAppend" class="ms-1" />
    @elseif(!$loading && $cleanIcon && $iconAppend)
        <x-bs::icon :name="$cleanIcon" class="ms-1" />
    @endif
</{{ $tag }}>
