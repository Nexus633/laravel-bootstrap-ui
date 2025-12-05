@props(['options' => []])

@php
    // Spezifische Defaults für Donuts (Achsen ausblenden)
    $donutDefaults = [
        'scales' => [
            'x' => ['display' => false],
            'y' => ['display' => false],
        ],
        'cutout' => '70%', // Macht den Donut dünner (moderner)
    ];
    
    // Merge mit User-Optionen
    $finalOptions = array_replace_recursive($donutDefaults, $options);
@endphp

<x-bs::chart type="doughnut" :options="$finalOptions" {{ $attributes }} />
