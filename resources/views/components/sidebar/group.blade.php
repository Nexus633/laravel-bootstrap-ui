@props(['label', 'icon' => null, 'prefix' => null])

@php
    $id = 'grp-' . md5($label);
    
    // Wenn wir z.B. unter /users/create sind und prefix="users*" ist -> Open = true
    $isOpen = $prefix && request()->is($prefix);
@endphp

<li class="nav-item my-1">
    <button
        class="nav-link d-flex align-items-center gap-3 w-100 text-start text-body-secondary hover-bg-body-secondary px-3 py-2 rounded-3 {{ $isOpen ? '' : 'collapsed' }}"
        data-bs-toggle="collapse"
        data-bs-target="#{{ $id }}"
        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
    >
        @if($icon)
            <x-bs::icon :name="$icon" size="1.1rem" class="text-muted" />
        @endif

        <span class="flex-grow-1" style="font-size: 0.95rem;">{{ $label }}</span>

        <x-bs::icon name="chevron-right" class="chevron-icon small text-muted" style="font-size: 0.75rem;" />
    </button>

    <div class="collapse {{ $isOpen ? 'show' : '' }}" id="{{ $id }}">
        <ul class="nav flex-column ms-3 ps-3 border-start mt-1 gap-1">
            {{ $slot }}
        </ul>
    </div>
</li>

@once
    <style>
        button[aria-expanded="true"] .chevron-icon { transform: rotate(90deg); }
        .chevron-icon { transition: transform 0.2s ease; }
    </style>
@endonce
