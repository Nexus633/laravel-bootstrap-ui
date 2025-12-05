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
    $coreJsUrl = $getAsset('js/ui-core.js');

    // Server-Side Theme Check (für Session-Persistenz)
    $serverTheme = session('bs-theme');
@endphp

<link href="{{ $cssUrl }}" rel="stylesheet">

<link href="{{ $iconUrl }}" rel="stylesheet">

@if($serverTheme)
    <script>
        document.documentElement.setAttribute("data-server-theme", "{{ $serverTheme }}");
    </script>
@endif

<script src="{{ $coreJsUrl }}"></script>
