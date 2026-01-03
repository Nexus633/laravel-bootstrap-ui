@props([
    'height' => '200px',
    'placeholder' => null,
    'theme' => config('bootstrap-ui.editor.theme'), // oder 'bubble'
    'toolbar' => config('bootstrap-ui.editor.modules.toolbar'),
    'history' => config('bootstrap-ui.editor.modules.history'),
    'syntax' => config('bootstrap-ui.editor.modules.syntax'),
])

@php
    $id = $attributes->getOrCreateId('editor-');
    $wireModel = $attributes->wire('model');
    $alpineValue = 'null';
    $debounceTime = 0; // Standard: 0 (bzw. Livewire Default wenn live)

    if ($wireModel->value()) {
        $isLive = $wireModel->hasModifier('live');
        // Hier bauen wir NUR .live an, KEIN debounce (das macht Syntaxfehler im JS)
        $alpineValue = "\$wire.entangle('" . $wireModel->value() . "')" . ($isLive ? '.live' : '');

        // 2. Prüfen auf .debounce für die JSON-Config
        if ($wireModel->hasModifier('debounce')) {
            $debounceTime = 250; // Livewire Standard
            // Wir suchen nach Modifiern wie "500ms" oder "1000ms"
            foreach ($wireModel->modifiers() as $mod) {
                if (preg_match('/^(\d+)ms?$/', $mod, $matches)) {
                    $debounceTime = (int)$matches[1];
                }
            }
        }
    }

    // 2. Config Logik (PHP Array bauen)
    // Wir bauen hier das komplette Config-Objekt für JS
    $editorConfig = [
        'theme' => $theme,
        'placeholder' => $placeholder,
        'height' => $height,
        'debounce' => $debounceTime,
        'modules' => [
            'toolbar' => $toolbar,
            'history' => $history,
            'syntax' => $syntax
        ]
    ];
@endphp

<div
    {{-- WICHTIG 1: wire:key bindet dieses Element fest an eine ID --}}
    wire:key="{{ $id }}"
    {{-- WICHTIG 2: ID für das Element setzen --}}
    id="{{ $id }}"
    {{-- WICHTIG 3: wire:ignore sagt: "Inhalt nie anfassen" --}}
    wire:ignore
        {{-- Wir übergeben EIN Objekt mit 'model' und 'config' --}}
    x-data="bsEditor({{ $alpineValue }})"
    data-config="{{ json_encode($editorConfig) }}"
    class="mb-3 bs-quill-wrapper"
>
    {{-- Der Container für Quill --}}
    <div x-ref="quillEditor"></div>

    {{-- Hidden Input für klassische Formular-Submits (Fallback) --}}
    @if($attributes->has('name'))
        <x-bs::input simple type="hidden" name="{{ $attributes->get('name') }}" x-bind:value="content" />
    @endif
</div>
