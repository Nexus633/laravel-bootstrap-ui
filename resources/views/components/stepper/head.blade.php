<div class="position-relative d-flex justify-content-between mb-2">
    {{-- Die Linie im Hintergrund --}}
    <div class="position-absolute top-50 start-0 w-100 border-top border-2 border-body-secondary z-0" style="transform: translateY(-50%);"></div>

    {{-- Container für die Items (mit Reference für Alpine zum Zählen) --}}
    <div x-ref="head" class="d-flex justify-content-between w-100 position-relative z-1">
        {{ $slot }}
    </div>
</div>
