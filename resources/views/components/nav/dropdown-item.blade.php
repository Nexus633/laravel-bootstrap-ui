@props([
    'trigger' => null, // z.B. "password" oder "#password"
    'href' => '#',
    'disabled' => false,
])

{{-- Wir holen uns den aktuellen State vom Parent (Nav) --}}
@aware(['current' => null])

@php
    use Illuminate\Support\Str;

    // 1. Clean Name für Livewire/Vergleich (ohne Hash)
    $cleanName = null;
    $bsTarget = null;
    $isTab = !empty($trigger);

    if ($isTab) {
        $cleanName = Str::after($trigger, '#');
        $bsTarget = Str::startsWith($trigger, '#') ? $trigger : '#' . $cleanName;
    }

    // 2. PHP Active Check (gegen Flackern)
    $phpActive = ($isTab && $current === $cleanName);

    // 3. Klassen
    $classes = ['dropdown-item'];
    if ($phpActive) $classes[] = 'active'; // Bootstrap nutzt auch bei Dropdowns .active
    if ($disabled) $classes[] = 'disabled';
@endphp

<li>
    @if($isTab)
        {{-- TAB LOGIK (Mit Alpine Sync) --}}
        <button
                type="button"
                {{-- MARKIERUNG: Das ist unser Signal für den Parent! --}}
                @if($phpActive) data-active-item="true" @endif
                {{-- WICHTIG: Wir hinterlegen den Wert im DOM --}}
                data-nav-value="{{ $cleanName }}"
                {{-- Alpine: Sofort Active setzen und an Livewire funken --}}
                :class="{ 'active': activeTab === '{{ $cleanName }}' }"
                @click="activeTab = '{{ $cleanName }}'"

                {{-- Bootstrap Logic --}}
                data-bs-toggle="tab"
                data-bs-target="{{ $bsTarget }}"
                role="tab"

                {{ $attributes->class($classes) }}
        >
            {{ $slot }}
        </button>
    @else
        {{-- NORMALER LINK (Kein Tab, kein Livewire Sync nötig) --}}
        <a
                href="{{ $href }}"
                {{ $attributes->class($classes) }}
                @if($disabled) tabindex="-1" aria-disabled="true" @endif
        >
            {{ $slot }}
        </a>
    @endif
</li>
