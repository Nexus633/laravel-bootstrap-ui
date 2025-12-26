@props([
    'href' => '#',
    'label' => null,
    'icon' => null,
    'iconAppend' => false, // Icon rechts statt links
    'target' => null,

    // 1. Farben (Booleans)
    'primary' => false, 'secondary' => false, 'success' => false, 
    'danger' => false, 'warning' => false, 'info' => false, 
    'light' => false, 'dark' => false, 'body' => false, // Passt sich der Textfarbe an

    // 2. Styling
    'bold' => false,
    'small' => false,
    'italic' => false,
    
    // 3. Dekoration
    'underline' => false,    // Erzwingt Unterstreichung immer
    'noUnderline' => false,  // Entfernt Unterstreichung (auch bei Hover)
    'offset' => false,       // Fügt etwas Abstand zur Unterstreichung hinzu (hübscher)

    // 4. Utilities
    'stretched' => false,    // Macht das Elternelement (z.B. Card) klickbar
])

@php
    $icon = $attributes->get('icon:prepend', $icon);
    $iconAppend = $attributes->get('icon:append', $iconAppend);
    $attributes = $attributes->except(['icon:prepend', 'icon:append']);

    $classes = [];

    // --- FARBEN (Bootstrap link-* Klassen) ---
    // Diese Klassen kümmern sich automatisch um Hover-States
    if ($primary)   $classes[] = 'link-primary';
    elseif ($secondary) $classes[] = 'link-secondary';
    elseif ($success)   $classes[] = 'link-success';
    elseif ($danger)    $classes[] = 'link-danger';
    elseif ($warning)   $classes[] = 'link-warning';
    elseif ($info)      $classes[] = 'link-info';
    elseif ($light)     $classes[] = 'link-light';
    elseif ($dark)      $classes[] = 'link-dark';
    elseif ($body)      $classes[] = 'link-body-emphasis';
    // Default: Standard Browser Link Blau (keine Klasse nötig)

    // --- TYPOGRAFIE ---
    if ($bold)   $classes[] = 'fw-bold';
    if ($small)  $classes[] = 'small';
    if ($italic) $classes[] = 'fst-italic';

    // --- DEKORATION ---
    if ($noUnderline) {
        $classes[] = 'text-decoration-none';
    } elseif ($underline) {
        $classes[] = 'text-decoration-underline';
    } else {
        // Standard: Bootstrap unterstreicht meist bei Hover.
        // offset sorgt für besseren Lesbarkeits-Abstand
        if ($offset) $classes[] = 'link-offset-2'; 
    }

    // --- UTILITIES ---
    if ($stretched) $classes[] = 'stretched-link';

    // --- TARGET HANDLING ---
    // Sicherheits-Attribut bei _blank automatisch hinzufügen
    $rel = ($target === '_blank') ? 'noopener noreferrer' : null;
@endphp

<a href="{{ $href }}"
   @if($target) target="{{ $target }}" @endif
   @if($rel) rel="{{ $rel }}" @endif
        {{ $attributes->class($classes) }}
>
    @if($icon && !$iconAppend)
        <x-bs::icon :name="$icon" class="me-1"/>
    @endif

    {{ $label ?? $slot }}

    @if($icon && $iconAppend)
        <x-bs::icon :name="$icon" class="ms-1"/>
    @endif
</a>
