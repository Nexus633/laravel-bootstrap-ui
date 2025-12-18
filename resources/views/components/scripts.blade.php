@props([
    'charts' => config('bootstrap-ui.scripts.charts', false), // Optional: Chart.js laden
    'editor' => config('bootstrap-ui.scripts.editor', false), // Optional: Quill Editor laden
])

@php

    $getAsset = function($path) {
        $publicPath = 'vendor/nexus633/bootstrap-ui/' . $path;
        if (file_exists(public_path($publicPath))) {
            return asset($publicPath);
        }
        return route('bs-ui.asset', ['path' => $path]);
    };

    $bsUrl = $getAsset('js/bootstrap.bundle.min.js');
    $chartUrl = $charts ? $getAsset('js/chart.umd.js') : null;
    $editorUrl = $editor ? $getAsset('js/editor/quill.js') : null;
    $highlightUrl = $editor ? $getAsset('js/editor/highlight.min.js') : null;

@endphp
<!-- BootstrapUI Scripts -->
<script src="{{ $bsUrl }}"></script>

@if($charts && $chartUrl)
    <script src="{{ $chartUrl }}"></script>
@endif

@if($editor && $editorUrl)
    @if(config('bootstrap-ui.editor.modules.syntax'))
        <script src="{{ $highlightUrl }}"></script>
    @endif
    <script src="{{ $editorUrl }}"></script>
@endif
<!-- BootstrapUI Scripts -->
