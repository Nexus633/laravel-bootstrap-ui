@props([
    'href' => '#',
    'active' => false,
    'disabled' => false,
])

<li class="nav-item">
    <a
        href="{{ $href }}"
        {{ $attributes->class([
            'nav-link',
            'active' => $active,
            'disabled' => $disabled
        ]) }}
        @if($active) aria-current="page" @endif
    >
        {{ $slot }}
    </a>
</li>
