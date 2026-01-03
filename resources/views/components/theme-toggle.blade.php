@props([
    'variant' => 'outline-secondary',
    'size' => null,
    // Fallbacks
    'lightIcon' => 'sun-fill',
    'darkIcon' => 'moon-stars-fill',
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $field = BootstrapUi::make();
    $id = $attributes->getOrCreateId('btn-');

    $field->addClass('btn')
          ->addClassWhen($variant, 'btn-' . $variant)
          ->addClassWhen($size, 'btn-' . $size);

    // Falls Attribut vorhanden, entsprechend nehmen
    $lightIcon = Icon::toClass($attributes->pluck('icon:light', $lightIcon));
    $darkIcon = Icon::toClass($attributes->pluck('icon:dark', $darkIcon));

@endphp

<button
    id="{{ $id }}"
    type="button"
    {{ $attributes->class($field->getClasses()) }}
    x-data="{
        theme: document.documentElement.getAttribute('data-bs-theme') || 'light'
    }"
    x-on:click="
        theme = window.toggleBootstrapTheme();
    "
>
    {{-- Light Icon --}}
    <x-bs::icon :name="$lightIcon" x-show="theme === 'light'" />

    {{-- Dark Icon --}}
    <x-bs::icon :name="$darkIcon" x-show="theme === 'dark'" style="display: none;"/>

    @if(!$slot->isEmpty())
        <x-bs::text span class="ms-1">{{ $slot }}</x-bs::text>
    @endif
</button>
