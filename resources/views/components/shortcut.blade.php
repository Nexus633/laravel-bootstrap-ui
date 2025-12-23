@props([
    'key' => null,
    'wire' => null,
    'dispatch' => null,
    'click' => null,
    'focus' => null,
    'js' => null,
    'prevent' => true,
    'stop' => false,
])

@php
    use Nexus633\BootstrapUi\Support\ShortcutSanitizer;

    // --- ACTION BUILDER ---
    $rawCode = '';

    if ($js) {
        $rawCode = $js;
    } elseif ($wire) {
        $method = str_ends_with($wire, ')') ? $wire : $wire . '()';
        $rawCode = "\$wire.{$method}";
    } elseif ($dispatch) {
        $rawCode = "\$dispatch('{$dispatch}')";
    } elseif ($click) {
        $rawCode = "document.querySelector('{$click}')?.click()";
    } elseif ($focus) {
        $rawCode = "document.querySelector('{$focus}')?.focus()";
    } elseif ($key && $slot->isNotEmpty()) {
        // Slot Inhalt als Code (Invisible Mode)
        $rawCode = $slot->toHtml();
    }

    // --- SECURITY CHECK ---
    // Hier wird der Code geprüft, bevor er gerendert wird.
    // Enthält er "eval" oder "document.cookie", wird er bereinigt.
    $safeActionCode = ShortcutSanitizer::validate($rawCode);

@endphp

<div
    {{ $attributes }}
    x-data="bsShortcut({
        key: @js($key),
        prevent: @js($prevent),
        stop: @js($stop),
        handler: () => {
            {{-- Nur der gesicherte Code wird ausgegeben --}}
            {!! $safeActionCode !!}
        }
    })"
    style="{{ $key ? 'display: none;' : 'display: contents;' }}"
>
    @if(!$key)
        {{ $slot }}
    @endif
</div>
