@props([
    'charts' => false, // Optional: Chart.js laden
])

@php
    $getAsset = function($path) {
        $publicPath = 'vendor/nexus633/bootstrap-ui/' . $path;
        if (file_exists(public_path($publicPath))) {
            return asset($publicPath);
        }
        return route('bs-ui.asset', ['path' => $path]);
    };

    $bsUrl = $getAsset('js/bootstrap-ui.js');
    $bsUrl = $getAsset('js/bootstrap.bundle.min.js');
    $chartUrl = $charts ? $getAsset('js/chart.umd.js') : null;
@endphp

<script src="{{ $bsUrl }}"></script>

@if($charts && $chartUrl)
    <script src="{{ $chartUrl }}"></script>
@endif
