@props([
    'current' => null,   // WICHTIG: Der PHP-Wert für das Server-Rendering
    'type' => 'tabs',
    'fill' => false,
    'justified' => false,
    'vertical' => false,
])

@php
     // 1. Boolean Flags prüfen (Vereinfacht ohne 'as:')
     // Das liest sich im HTML schöner: <x-bs::nav tabs ...>
     $tabs = $attributes->get('tabs');
     $pills = $attributes->get('pills');
     $underline = $attributes->get('underline');

     // 2. Type setzen (Flags überschreiben den Prop)
     if ($tabs)      $type = 'tabs';
     elseif ($pills) $type = 'pills';
     elseif ($underline) $type = 'underline';

     // 3. Attribute bereinigen (WICHTIG!)
     // Damit 'tabs="true"' nicht im <ul> landet
     $attributes = $attributes->except(['tabs', 'pills', 'underline']);


     $classes = ['nav'];
     if($type) $classes[] = 'nav-'.$type;
     if($fill) $classes[] = 'nav-fill';
     if($justified) $classes[] = 'nav-justified';
     if($vertical) {
         $classes[] = 'flex-column';
         if($type === 'pills' || $type === 'tabs') $classes[] = 'me-3';
     }

     // Wir holen uns wire:model
     $wireModel = $attributes->wire('model');
@endphp

<ul
        {{ $attributes->except('wire:model')->class($classes) }}
        role="tablist"

        {{--
           HIER IST DER TRICK:
           Wir erstellen eine Alpine Variable 'activeTab'.
           Sie ist mit Livewire verknüpft (entangle).
           Wenn wire:model fehlt, ist sie null.
        --}}
        x-data="{
        activeTab: @if($wireModel->directive()) @entangle($wireModel->value()) @else null @endif
    }"
>
    {{ $slot }}
</ul>
