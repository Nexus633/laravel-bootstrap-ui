@props(['options' => []])

@php
    $pieDefaults = [
        'scales' => [
            'x' => ['display' => false],
            'y' => ['display' => false],
        ]
    ];
    $finalOptions = array_replace_recursive($pieDefaults, $options);
@endphp

<x-bs::chart type="pie" :options="$finalOptions" {{ $attributes }} />
