@props([
    'label' => null,
    'icon' => null,
    'variant' => 'secondary',
    'size' => null,
    'align' => 'start',
    'noCaret' => false,
    'direction' => null,
    'nav' => false, // NEU: Schaltet in den Navbar-Modus
])

@aware([
    // aus der nav komponente
    'start' => false,
    'end' => false
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $noCaret = $attributes->pluck('no:caret', $noCaret);
    $iconClass = Icon::toClass($icon);

    $wrapperUi = BootstrapUi::make();
    $triggerUi = BootstrapUi::make();
    $menuUi = BootstrapUi::make()->addClass('dropdown-menu', 'shadow');

    $triggerAttrs = [];

    if ($nav) {
        $wrapperTag = 'li';
        $wrapperUi->addClass('nav-item', 'dropdown');

        $triggerTag = 'a';
        $triggerUi->addClass('nav-link')
                  ->addData('href', '#')
                  ->addData('role', 'button');
    } else {
        $wrapperTag = 'div';
        $wrapperUi->addClass(
            match ($direction) {
                'up' => 'dropup',
                'up-center' => 'dropup-center dropup',
                'left' => 'dropstart',
                'right' => 'dropend',
                'center' => 'dropdown-center',
                default => 'dropdown'
        });

        $triggerTag = 'button';
        $triggerUi->addClass('btn', 'btn-' . $variant)
                  ->addClassWhen($size, 'btn-' . $size)
                  ->addData('type', 'button');
    }

    $triggerUi->addClassWhen(!$noCaret, 'dropdown-toggle');
    $menuUi->addClassWhen($align === 'end' || $end, 'dropdown-menu-end')
           ->addClassWhen($align === 'start' || $start, 'dropdown-menu-start');
@endphp

<{{ $wrapperTag }} {{ $attributes->class($wrapperUi->getClasses()) }}>
    <{{ $triggerTag }}
        {{ $attributes->merge($triggerUi->getDataAttributes())->class($triggerUi->getClasses()) }}
        {{-- Fügt Farb-Logik für aktive Nav-Pills hinzu --}}
        @if($nav)
        x-bind:class="{
            ['text-bg-' + tabVariant]: $el.classList.contains('active') && tabType === 'pills' && tabVariant,
            ['text-' + tabVariant]: $el.classList.contains('active') && tabType === 'tabs' && tabVariant,
        }"
        @endif
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        @if($iconClass)
            <i class="bi {{ $iconClass }} @if($label) me-1 @endif"></i>
        @endif
        {{ $label }}
    </{{ $triggerTag }}>

    <ul class="{{ $menuUi->getClasses() }}">
        {{ $slot }}
    </ul>
</{{ $wrapperTag }}>
