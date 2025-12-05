@props([
    'type' => 'line',
    'labels' => [],
    'datasets' => [],
    'height' => 300,
    'options' => [],
])

@php
    $defaultOptions = [
        'responsive' => true,
        'maintainAspectRatio' => false,
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'bottom',
                'labels' => [
                    'usePointStyle' => true,
                    'padding' => 20
                    ]
                ],
            'tooltip' => [
                'enabled' => true,
                'mode' => 'index',
                'intersect' => false
            ]
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'grid' => [
                    'drawBorder' => false
                ]
            ],
            'x' => [
                'grid' => [
                    'display' => false
                ]
            ]
        ]
    ];
    $finalOptions = array_replace_recursive($defaultOptions, $options);
@endphp

<div wire:key="chart-{{ uniqid()}}"
        {{ $attributes->class(['w-100']) }}
        style="height: {{ $height }}px; position: relative;"
        {{-- WICHTIG: Wir speichern den Typ im Data-Attribut, damit JS ihn lesen kann --}}
        data-type="{{ $type }}"
        {{-- WICHTIG: Hier rufen wir jetzt die globale Funktion auf. Das HTML bleibt sauber! --}}
        x-data="bsChart(
        @js($labels),
        @js($datasets),
        @js($finalOptions)
    )"
        x-effect="render()"
>
    <canvas x-ref="canvas"></canvas>
</div>
