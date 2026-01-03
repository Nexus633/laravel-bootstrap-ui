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
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;
    
    $field = BootstrapUi::make();

    // --- LOGIC ---
    if ($collapse && $offcanvas) {
        throw new InvalidArgumentException(__('bs::bootstrap-ui.button.InvalidArgumentException'));
    }

    $iconPrepend = $attributes->pluck('icon:prepend');
    $iconAppend = $attributes->pluck('icon:append');

    $cleanIcon = $icon ? Icon::toClass($icon) : null;
    $cleanIconPrepend = $iconPrepend ? Icon::toClass($iconPrepend) : null;
    $cleanIconAppend = $iconAppend ? Icon::toClass($iconAppend) : null;

    $isLink = $attributes->has('href');
    $tag = $isLink ? 'a' : 'button';

    // --- CLASSES ---
    if ($pagination) {
        $field->addClass('page-link')->addClassWhen($disabled, 'disabled');
    } else {
        $actualVariant = $outline && !str_contains($variant, 'outline') ? 'outline-' . $variant : $variant;
        $field->addClass('btn', 'btn-' . $actualVariant)
              ->addClassWhen($size, 'btn-' . $size)
              ->addClassWhen($relative, 'position-relative')
              ->addClassWhen($align === 'right', 'float-end')
              ->addClassWhen($align === 'block', ['w-100', 'd-block'])
              ->addClassWhen($align === 'center', ['d-block', 'mx-auto']);
    }

    // --- ATTRIBUTES ---
    if ($collapse) {
        $target = (str_starts_with($collapse, '.') || str_starts_with($collapse, '#')) ? $collapse : '#' . $collapse;
        $field->addData('data-bs-toggle', 'collapse')
              ->addData('data-bs-target', $target)
              ->addData('aria-expanded', 'false')
              ->addData('aria-controls', trim($target, '#.'))
              ->addDataWhen($isLink && !$attributes->has('href'), 'href', $target);
    }
    if ($offcanvas) {
        $target = str_starts_with($offcanvas, '#') ? $offcanvas : '#' . $offcanvas;
        $field->addData('data-bs-toggle', 'offcanvas')
              ->addData('data-bs-target', $target)
              ->addData('aria-controls', trim($target, '#.'))
              ->addDataWhen($isLink && !$attributes->has('href'), 'href', $target);
    }

    if ($dismiss) {
        $field->addData('data-bs-dismiss', $dismiss);
    }
    if (($dismiss || $collapse || $offcanvas) && $slot->isEmpty()) {
        $field->addData('aria-label', $ariaLabel ?? ($dismiss ? 'Close' : 'Toggle'));
    }

    if ($disabled) {
        $field->addDataWhen($isLink, 'aria-disabled', 'true')
              ->addDataWhen($isLink, 'tabindex', '-1')
              ->addDataWhen(!$isLink, 'disabled', true);
    }

    // --- ICONS ---
    $hasIconPrepend = $cleanIconPrepend || ($cleanIcon && !$iconAppend);
    $hasIconAppend = $cleanIconAppend || ($cleanIcon && !$iconPrepend && $iconAppend);
@endphp

<{{ $tag }}
    @if($tag !== 'a') type="{{ $type }}" @endif
    {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}
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

    @if(!$loading && $hasIconPrepend)
        <x-bs::icon :name="$cleanIconPrepend ?: $cleanIcon" class="me-1" />
    @endif

    {{ $slot }}

    @if(!$loading && $hasIconAppend)
        <x-bs::icon :name="$cleanIconAppend ?: $cleanIcon" class="ms-1" />
    @endif
</{{ $tag }}>
