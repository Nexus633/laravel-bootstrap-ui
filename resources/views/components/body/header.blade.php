@props([
    'title' => config('app.name'),
    'icon' => 'list'
])

<header class="flex-shrink-0 bg-body border-bottom sticky-top">
    <div class="d-flex align-items-center w-100">

        <div class="d-lg-none p-2 border-end">
            <x-bs::button
                variant="link"
                class="text-decoration-none text-body"
                :icon="$icon"
                offcanvas="sidebar-nav"
            />
        </div>

        {{-- B) Deine Topbar / Navbar --}}
        <div class="flex-grow-1" style="min-width: 0;">
            @if(!$slot->isEmpty())
                {{ $slot }}
            @else
                {{-- Fallback: Nur Titel anzeigen, wenn keine Navbar da ist --}}
                <div class="px-3 py-2 fw-semibold d-lg-none">
                    {{ $title }}
                </div>
            @endif
        </div>
    </div>
</header>
