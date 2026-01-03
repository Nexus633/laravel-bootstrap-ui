@props([
    'href' => '#',
    'activeVariant' => 'body-secondary',
    'name' => null,
    'icon' => null,
    'badge' => null,
    'badgeVariant' => 'danger',
    'exact' => false
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    // CustomAttributs
    $badgeVariant = $attributes->pluck('badge:variant', $badgeVariant);
    $activeVariant = $attributes->pluck('active:variant', $activeVariant);

    // 1. URL Normalisieren
    $path = ltrim($href, '/');

    // 2. Active Check
    // Wenn exact=true (z.B. Dashboard), muss es genau matchen.
    // Sonst matcht es auch Unterseiten (z.B. /users/create matcht /users).
    $isActive = $exact ? request()->is($path) : request()->is($path . '*');

    $field->addClass('nav-link', 'd-flex', 'align-items-center', 'gap-3', 'py-2', 'px-3', 'rounded-3', 'transition-base')
          ->addClassWhen($isActive, ['bg-' . $activeVariant, 'text-white', 'shadow-sm', 'fw-medium'])
          ->addClassWhen(!$isActive, ['text-body-secondary', 'hover-bg-body-secondary']);
@endphp

<li class="nav-item">
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $field->getClasses()]) }}>

        @if($icon)
            <x-bs::icon :name="$icon" size="1.1rem" class="{{ $isActive ? 'text-white' : 'text-body-tertiary' }}" />
        @endif

        <span class="flex-grow-1 text-truncate" style="font-size: 0.95rem;">
            @isset($name)
                {{ $name }}
            @else
                {{ $slot }}
            @endisset
        </span>

        @if($badge)
            <x-bs::badge :variant="$badgeVariant" >
                {{ $badge }}
            </x-bs::badge>
        @endif
    </a>
</li>
