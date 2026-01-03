@props([
    'href' => null,
    'active' => false,
    'icon' => null,
    'title' => null // Optional, falls man nicht den Slot nutzen will
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $field = BootstrapUi::make();
    // Wenn kein Link da ist, gehen wir davon aus, dass es die aktive Seite ist
    $isActive = $active || is_null($href);
    
    $icon = Icon::toClass($icon);

    $field->addClass('breadcrumb-item')
          ->addClassWhen($isActive, 'active')
          ->addDataWhen($isActive, 'aria-current', 'page');

@endphp

<li {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
    @if($isActive)
        {{-- Aktive Seite (Nur Text/Icon) --}}
        @if($icon) <x-bs::icon :name="$icon" /> @endif
        {{ $title ?? $slot }}
    @else
        {{-- Link --}}
        <x-bs::link :href="$href" no-underline>
            @if($icon) <x-bs::icon :name="$icon" /> @endif
            {{ $title ?? $slot }}
        </x-bs::link>
    @endif
</li>
