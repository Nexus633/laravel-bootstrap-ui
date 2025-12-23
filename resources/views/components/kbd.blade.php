@props([
    'label' => null,
    
    // Modifiers
    'ctrl' => false, 'shift' => false, 'alt' => false,
    'meta' => false, 'cmd' => false, 'option' => false,

    // Keys
    'enter' => false, 'esc' => false, 'tab' => false,
    'backspace' => false, 'capslock' => false, 'space' => false,
    'arrowUp' => false, 'arrowDown' => false, 'arrowLeft' => false, 'arrowRight' => false,
    'delete' => false,

    // Style
    'small' => false,
    'mac' => false,
])

@php
    if ($cmd) $meta = true;

    $keys = [];

    // Helper: Wir speichern den "Logik-Namen" (val) für JS und den "Anzeige-Namen" (view) für den User
    $add = function($val, $view) use (&$keys) {
        $keys[] = ['val' => $val, 'view' => $view];
    };

    // A. Modifikatoren
    if ($mac) {
        if ($ctrl)   $add('ctrl', '⌃');
        if ($alt || $option) $add('alt', '⌥');
        if ($shift)  $add('shift', '⇧');
        if ($meta)   $add('meta', '⌘');
    } else {
        if ($ctrl)   $add('ctrl', 'Strg'); // Oder Ctrl
        if ($alt || $option) $add('alt', 'Alt');
        if ($shift)  $add('shift', 'Shift');
        if ($meta)   $add('meta', 'Win');
    }

    // B. Special Keys
    if ($enter)     $add('enter', $mac ? '⏎' : 'Enter');
    if ($esc)       $add('escape', 'Esc');
    if ($tab)       $add('tab', $mac ? '⇥' : 'Tab');
    if ($backspace) $add('backspace', $mac ? '⌫' : 'Backspace');
    if ($capslock)  $add('capslock', $mac ? '⇪' : 'Caps Lock');
    if ($space)     $add('space', 'Space');
    if ($delete)    $add('delete', 'Entf');

    if ($arrowUp)    $add('arrowup', '↑');
    if ($arrowDown)  $add('arrowdown', '↓');
    if ($arrowLeft)  $add('arrowleft', '←');
    if ($arrowRight) $add('arrowright', '→');

    // C. Content (z.B. "S")
    $content = $label ?? trim($slot);
    if (!empty($content)) {
        // Logik-Wert immer klein (s), Anzeige wie übergeben (S)
        $add(strtolower($content), $content);
    }
@endphp

<span {{ $attributes->class(['user-select-none']) }}>
    @foreach($keys as $index => $k)
        {{-- HIER IST DIE MAGIE: data-shortcut-key --}}
        <kbd
            @if($small) class="fs-6" @endif
            data-shortcut-key="{{ $k['val'] }}"
        >
            {{ $k['view'] }}
        </kbd>

        @if($index < count($keys) - 1)
            <x-bs::text small variant="body-secondary" class="mx-1">+</x-bs::text>
        @endif
    @endforeach
</span>
