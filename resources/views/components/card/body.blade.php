@props([
    'title' => null,
    'subtitle' => null,
    'text' => null,
    'icon' => null, // Icon neben dem Titel
])

@php
    use Nexus633\BootstrapUi\Facades\Icon;
    $icon = Icon::toClass($icon);
@endphp

<div {{ $attributes->class(['card-body']) }}>

    {{-- Titel --}}
    @if($title)
        <h5 class="card-title">
            @if($icon) <x-bs::icon :name="$icon" class="me-1" /> @endif
            {{ $title }}
        </h5>
    @endif

    {{-- Untertitel (mit Standard Bootstrap Styling) --}}
    @if($subtitle)
        <h6 class="card-subtitle mb-2 text-body-secondary">
            {{ $subtitle }}
        </h6>
    @endif

    {{-- Text Paragraph --}}
    @if($text)
        <p class="card-text">{{ $text }}</p>
    @endif

    {{-- Der eigentliche Inhalt --}}
    {{ $slot }}
</div>
