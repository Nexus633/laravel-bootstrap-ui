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
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon; // Diese Zeile ist möglicherweise redundant, wenn Icon nicht direkt hier verwendet wird, aber schadet nicht.

    $icon = $attributes->pluck('icon:prepend', $icon);
    $iconAppend = $attributes->pluck('icon:append', $iconAppend);

    $field = BootstrapUi::make();

    // --- FARBEN ---
    $colorClass = null;
    if ($primary)   $colorClass = 'link-primary';
    elseif ($secondary) $colorClass = 'link-secondary';
    elseif ($success)   $colorClass = 'link-success';
    elseif ($danger)    $colorClass = 'link-danger';
    elseif ($warning)   $colorClass = 'link-warning';
    elseif ($info)      $colorClass = 'link-info';
    elseif ($light)     $colorClass = 'link-light';
    elseif ($dark)      $colorClass = 'link-dark';
    elseif ($body)      $colorClass = 'link-body-emphasis';

    $field->addClassWhen($colorClass, $colorClass);

    // --- TYPOGRAFIE ---
    $field->addClassWhen($bold, 'fw-bold')
          ->addClassWhen($small, 'small')
          ->addClassWhen($italic, 'fst-italic');

    // --- DEKORATION ---
    if ($noUnderline) {
        $field->addClass('text-decoration-none');
    } elseif ($underline) {
        $field->addClass('text-decoration-underline');
    } else {
        $field->addClassWhen($offset, 'link-offset-2');
    }

    // --- UTILITIES ---
    $field->addClassWhen($stretched, 'stretched-link')
          ->addDataWhen($target, 'target', $target)
          ->addDataWhen(($target === '_blank'), 'rel', 'noopener noreferrer');

@endphp

<a href="{{ $href }}" {{ $attributes->class($field->getClasses())->merge($field->getDataAttributes()) }}>
    @if($icon && !$iconAppend)
        <x-bs::icon :name="$icon" class="me-1"/>
    @endif

    {{ $label ?? $slot }}

    @if($icon && $iconAppend)
        <x-bs::icon :name="$icon" class="ms-1"/>
    @endif
</a>
