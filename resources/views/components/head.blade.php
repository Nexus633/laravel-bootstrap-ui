@props([
    'editor' => config('bootstrap-ui.scripts.editor', false), // Optional: Quill Editor laden
])
@php
    // Helper: Asset URL ermitteln (Public oder Route)
    $getAsset = function($path) {
        $publicPath = 'vendor/nexus633/bootstrap-ui/' . $path;
        if (file_exists(public_path($publicPath))) {
            return asset($publicPath);
        }
        return route('bs-ui.asset', ['path' => $path]);
    };

    $cssUrl = $getAsset('css/bootstrap.min.css');
    $iconUrl = $getAsset('css/bootstrap-icons.min.css');
    $bsUrl = $getAsset('css/bootstrap-ui.css');
    $editorUrl= $getAsset('css/editor/quill.snow.css');

    $coreJsUrl = $getAsset('js/ui-core.js');
    $uiAlpineJsUrl = $getAsset('js/bs-ui-alpine.js');

    if(config('bootstrap-ui.editor.modules.syntax')){
        $theme = config('bootstrap-ui.editor.highlight.theme', 'atom-one-dark.min.css');
        if(empty($theme)){
            $theme = 'atom-one-dark.min.css';
        }
        $highlightThemeUrl = $getAsset('css/editor/highlight/'. $theme);
    }

    // Server-Side Theme Check (für Session-Persistenz)
    $serverTheme = session('bs-theme');
@endphp
<!-- BootstrapUI Styles & Scripts -->
<link href="{{ $cssUrl }}" rel="stylesheet">

<link href="{{ $iconUrl }}" rel="stylesheet">

<link href="{{ $bsUrl }}" rel="stylesheet">

@if($editor && $editorUrl)
    @if(config('bootstrap-ui.editor.modules.syntax'))
        <link href="{{ $highlightThemeUrl }}" rel="stylesheet">
    @endif
    <link href="{{ $editorUrl }}" rel="stylesheet">
@endif

@if($serverTheme)
    <script>
        document.documentElement.setAttribute("data-server-theme", "{{ $serverTheme }}");
    </script>
@endif

<script src="{{ $coreJsUrl }}"></script>
<script src="{{ $uiAlpineJsUrl }}"></script>

<!-- BootstrapUI Styles & Scripts -->
