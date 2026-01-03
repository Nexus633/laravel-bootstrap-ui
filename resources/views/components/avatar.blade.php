@props([
    'src' => null,
    'alt' => '',
    'name' => null,
    'size' => 'md',
    'shape' => 'circle',
    'variant' => 'secondary',
    'border' => false,
    'tooltip' => null,
    'placement' => 'top',
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;

    $field = BootstrapUi::make();
    
    $singleInitial = $attributes->pluck('initials:single');

    // Initials Logic
    $initials = '';
    if (!$src && $name) {
        if ($singleInitial) {
            $initials = strtoupper(mb_substr($name, 0, 1));
        } else {
            $parts = explode(' ', $name);
            foreach ($parts as $index => $part) {
                if ($index < 2 && !empty($part)) {
                    $initials .= strtoupper(mb_substr($part, 0, 1));
                }
            }
        }
    }

    // Auto-color Logic
    if ($variant === 'auto') {
        $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark', 'indigo', 'purple', 'pink', 'teal'];
        $seed = $name ?? 'default';
        $hash = crc32($seed);
        $variant = $colors[$hash % count($colors)];
    }

    // Size Logic
    $sizes = [
        'xs' => ['size' => '24px', 'font' => '0.75rem'],
        'sm' => ['size' => '32px', 'font' => '0.875rem'],
        'md' => ['size' => '48px', 'font' => '1.25rem'],
        'lg' => ['size' => '64px', 'font' => '1.75rem'],
        'xl' => ['size' => '96px', 'font' => '2.5rem'],
    ];
    $config = $sizes[$size] ?? $sizes['md'];
    
    // Use the helper for styles
    $field->addStyle('width', $config['size'])
          ->addStyle('height', $config['size'])
          ->addStyle('font-size', $config['font']);

    // Class Logic
    $shapes = [
        'circle'  => 'rounded-circle',
        'square'  => 'rounded-0',
        'rounded' => 'rounded',
    ];
    $shapeClass = $shapes[$shape] ?? $shape; // Allow custom shape classes

    $field->addClass('d-inline-flex', 'align-items-center', 'justify-content-center', 'flex-shrink-0', 'align-middle', $shapeClass)
          ->addClassWhen($border, ['border', 'border-2', 'border-white'])
          ->addClassWhen(!$src, 'text-bg-' . $variant);
@endphp

@php
    $renderContent = function ($isImage) use ($src, $alt, $name, $attributes, $field, $initials) {
        $finalStyles = $field->getStyles();
        $extraStyle = $isImage ? ' object-fit: cover;' : ' user-select: none;';
        
        $commonAttributes = $attributes->class($field->getClasses())->merge(['style' => $finalStyles . $extraStyle]);

        if ($isImage) {
            echo '<img src="' . e($src) . '" alt="' . e($alt ?? $name) . '" ' . $commonAttributes . '>';
        } else {
            echo '<div ' . $commonAttributes . '>';
            if ($initials) {
                echo e($initials);
            } else {
                echo '<x-bs::icon name="person-fill" />';
            }
            echo '</div>';
        }
    };
@endphp

@if($tooltip)
    <x-bs::tooltip :text="$tooltip" :placement="$placement">
        @if($src)
            {{ $renderContent(true) }}
        @else
            {{ $renderContent(false) }}
        @endif
    </x-bs::tooltip>
@else
    @if($src)
        {{ $renderContent(true) }}
    @else
        {{ $renderContent(false) }}
    @endif
@endif
