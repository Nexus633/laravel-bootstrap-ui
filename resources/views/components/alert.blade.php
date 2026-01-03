@props([
    'variant' => 'primary',
    'dismissible' => false,
    'icon' => null,
    'iconFs' => 4
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $field = BootstrapUi::make();

    // Icon logic remains the same
    if (!$icon) {
        $icon = Icon::toClass(match($variant) {
            'success' => 'check-circle-fill',
            'danger', 'error' => 'exclamation-triangle-fill',
            'warning' => 'exclamation-circle-fill',
            'info' => 'info-circle-fill',
            default => 'info-circle',
        });
    } else {
        $icon = Icon::toClass($icon);
    }

    $iconFs = $attributes->pluck('icon:fs', $iconFs);
    $realVariant = $variant === 'error' ? 'danger' : $variant;

    $field->addClass('alert', 'alert-' . $realVariant, 'd-flex', 'align-items-center')
          ->addClassWhen($dismissible, ['alert-dismissible', 'fade', 'show']);
@endphp

<div {{ $attributes->merge(['class' => $field->getClasses(), 'role' => 'alert']) }}>
    <x-bs::icon :name="$icon" class="flex-shrink-0 me-2" :size="$iconFs" />

    <div>
        {{ $slot }}
    </div>

    @if($dismissible)
        <x-bs::button.close dismiss="alert" />
    @endif
</div>
