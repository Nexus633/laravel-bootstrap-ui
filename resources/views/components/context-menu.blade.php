@props([
    'target'
])

{{-- 2. Das HTML Markup --}}
<div x-data="bsContextMenu('{{ $target }}')" wire:ignore>
    @teleport('body')
    <div x-show="isOpen"
         x-ref="menuPanel"
         @click.outside="close()"
         @scroll.window="close()"
         x-transition.opacity.duration.150ms
         class="dropdown-menu show"
         :style="style"
         style="display: none;"
    >
        {{ $slot }}
    </div>
    @endteleport
</div>
