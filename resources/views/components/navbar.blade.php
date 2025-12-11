@props([
    'expand' => 'lg',
    'sticky' => null,
    'fixed' => null,
    'container' => 'fluid', // fluid, sm, md... oder false
    'theme' => null,
    'bg' => null,
])

@php
    $classes = ['navbar'];

    if ($expand) $classes[] = 'navbar-expand-' . $expand;
    if ($sticky) $classes[] = 'sticky-' . $sticky;
    if ($fixed)  $classes[] = 'fixed-' . $fixed;

    if ($bg) {
        $classes[] = 'bg-' . $bg;
    } else {
        $classes[] = 'bg-body-tertiary';
    }
@endphp

<nav
    {{ $attributes->class($classes) }}
    @if($theme) data-bs-theme="{{ $theme }}" @endif
>
    @if($container)
        {{--
            LOGIK-MAPPING:
            Die Navbar übergibt 'container' als String (z.B. 'fluid' oder 'lg').
            Der Container will aber :fluid (bool) oder :size (string).
        --}}
        @php
            $isFluid = ($container === 'fluid');
            // Wenn es nicht 'fluid' ist, muss der String die Size sein (z.B. 'md')
            $sizeVal = (!$isFluid) ? $container : null;
        @endphp

        <x-bs::container :fluid="$isFluid" :size="$sizeVal">
            {{ $slot }}
        </x-bs::container>

    @else
        {{-- Kein Container gewünscht (container="false") --}}
        {{ $slot }}
    @endif
</nav>
