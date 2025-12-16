@props([
    'time' => null,
    'title' => null,
    'icon' => 'circle-fill', // Standard: Einfacher Punkt
    'variant' => 'primary',
    'simple' => false, // Wenn true: Kleineres Design ohne viel Text
])

{{-- ps-4: Abstand vom Strich nach rechts zum Inhalt --}}
<div {{ $attributes->class(['position-relative ps-4 mb-4']) }}>

    {{-- 
        DER PUNKT (DOT)
        top-0: Ganz oben am Element
        start-0: Ganz links (auf der Linie des Eltern-Containers)
        translate-middle-x: Zieht ihn 50% nach links, damit er MITTIG auf der Linie sitzt.
        bg-body: Wichtig, damit die Linie dahinter "unsichtbar" wird (Lücke-Effekt) oder man nutzt border.
    --}}
    <div
        class="position-absolute top-0 start-0 translate-middle-x d-flex align-items-center justify-content-center rounded-circle text-{{ $variant }} bg-body shadow-sm border border-{{ $variant }}"
        style="width: 32px; height: 32px; margin-top: -2px;"
    >
        <x-bs::icon :name="$icon" size="0.9rem" />
    </div>

    {{-- INHALT --}}
    <div>
        {{-- Header Zeile: Titel und Zeit --}}
        <div class="d-flex align-items-center justify-content-between mb-1">
            @if($title)
                <h6 class="mb-0 fw-semibold {{ $simple ? 'small' : '' }}">
                    {{ $title }}
                </h6>
            @endif

            @if($time)
                <small class="text-body-tertiary {{ $simple ? 'ms-2' : '' }}" style="font-size: 0.8rem;">
                    {{ $time }}
                </small>
            @endif
        </div>

        {{-- Slot / Beschreibung --}}
        <div class="text-body-secondary {{ $simple ? 'small' : '' }}">
            {{ $slot }}
        </div>
    </div>
</div>
