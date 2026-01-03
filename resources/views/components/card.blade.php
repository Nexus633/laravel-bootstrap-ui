@props([
    'title' => null,
    'subtitle' => null,
    'text' => null,
    'image' => null,
    'imgAlt' => '',
    'body' => true,
    'variant' => null,
    'icon' => null,
    'align' => null,    // 'start', 'center', 'end'
    // Wir definieren header/footer hier NICHT als props, 
    // damit sie nicht automatisch als Attribute behandelt werden, 
    // falls Slots genutzt werden. Wir holen sie manuell.
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $field = BootstrapUi::make();
    $field->addClass('card')
          ->addClassWhen($variant, 'text-bg-' . $variant)
          ->addClassWhen($align, 'text-' . $align);

    if ($attributes->has('class') && str_contains($attributes->get('class'), 'h-100')) {
        $field->addClass('h-100');
    }

    $iconClass = Icon::toClass($icon);

    $headerContent = $header ?? $attributes->pluck('header');
    $footerContent = $footer ?? $attributes->pluck('footer');
@endphp

<div {{ $attributes->class($field->getClasses()) }}>

    {{-- 1. Bild Oben --}}
    @if($image)
        <img src="{{ $image }}" class="card-img-top" alt="{{ $imgAlt }}">
    @endif

    {{-- 2. Header (Slot oder Prop) --}}
    @if($headerContent)
        <div class="card-header {{ $variant ? 'border-' . $variant : '' }}">
            {{ $headerContent }}
        </div>
    @endif

    {{-- 3. Body Bereich --}}
    @if($body)
        <div class="card-body">
            @if($title)
                <h5 class="card-title">
                    @if($iconClass) <i class="{{ $iconClass }} me-1"></i> @endif
                    {{ $title }}
                </h5>
            @endif

            @if($subtitle)
                <h6 class="card-subtitle mb-2 {{ $variant ? '' : 'text-body-secondary' }}">
                    {{ $subtitle }}
                </h6>
            @endif

            @if($text)
                <p class="card-text">{{ $text }}</p>
            @endif

            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif

    {{-- 4. Footer (Slot oder Prop) --}}
    @if($footerContent)
        <div class="card-footer {{ $variant ? 'border-' . $variant : '' }}">
            {{ $footerContent }}
        </div>
    @endif
</div>
