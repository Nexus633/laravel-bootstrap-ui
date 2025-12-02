@props([
    'variant' => 'outline-secondary',
    'size' => null,
    // Fallbacks
    'lightIcon' => 'bi bi-sun-fill',
    'darkIcon' => 'bi bi-moon-stars-fill',
])

@php
    $classes = 'btn btn-' . $variant;
    if($size) $classes .= ' btn-' . $size;

    // Falls Attribut vorhanden, entsprechend nehmen
    $lightIcon = $attributes->get('icon:light') ?? $lightIcon;
    $darkIcon = $attributes->get('icon:dark') ?? $darkIcon;

    // WICHTIG: Die Custom-Attribute entfernen, damit sie nicht im HTML landen
    $cleanAttributes = $attributes->except(['icon:light', 'icon:dark']);
@endphp

<button
    type="button"
    {{ $cleanAttributes->merge(['class' => $classes]) }}
    x-data="{
        theme: document.documentElement.getAttribute('data-bs-theme') || 'light'
    }"
    x-on:click="
        theme = window.toggleBootstrapTheme();
    "
>
    {{-- Light Icon --}}
    <i class="{{ $lightIcon }}" x-show="theme === 'light'"></i>

    {{-- Dark Icon --}}
    <i class="{{ $darkIcon }}" x-show="theme === 'dark'" style="display: none;"></i>

    @if(!$slot->isEmpty())
        <span class="ms-1">{{ $slot }}</span>
    @endif
</button>
