<x-bs::offcanvas
        id="sidebar-nav"
        placement="start"
        responsive="lg"
        class="border-end bg-body flex-shrink-0"
        style="width: 240px;" {{-- <-- SCHLANKER (war 280px) --}}
>
        {{ $slot }}
</x-bs::offcanvas>
