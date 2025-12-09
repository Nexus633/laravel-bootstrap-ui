@props([
    'trigger',      // z.B. "profile" oder "#profile"
    'disabled' => false,
])

{{-- Wir holen uns den PHP-Wert vom Parent --}}
@aware(['current' => null])

@php
    use Illuminate\Support\Str;

    // 1. Daten bereinigen (Hash entfernen für Vergleich)
    // '#profile' -> 'profile'
    $cleanName = Str::after($trigger, '#');

    // 2. Das Ziel für Bootstrap (Muss Hash haben)
    // 'profile' -> '#profile'
    $bsTarget = Str::startsWith($trigger, '#') ? $trigger : '#' . $cleanName;

    // 3. PHP Render Check (Gegen Flackern beim Laden)
    // Stimmt der aktuelle Server-Wert mit diesem Tab überein?
    $phpActive = ($current === $cleanName);

    $classes = ['nav-link'];
    // Wir setzen die Klasse active schon serverseitig, wenn es passt!
    if ($phpActive) $classes[] = 'active';
    if ($disabled) $classes[] = 'disabled';
@endphp

<li class="nav-item" role="presentation">
    <button
        {{--
            ALPINE LOGIK:
            :class -> Wenn Alpine Wert 'profile' ist, setze Klasse 'active'.
            @click -> Beim Klick setze Alpine Wert auf 'profile'.

            Das passiert SOFORT, bevor der Server antwortet.
        --}}
        :class="{ 'active': activeTab === '{{ $cleanName }}' }"
        @click="activeTab = '{{ $cleanName }}'"

        {{-- BOOTSTRAP LOGIK (nur für Pane-Wechsel nötig) --}}
        id="{{ $cleanName }}-tab"
        data-bs-toggle="tab"
        data-bs-target="{{ $bsTarget }}"
        type="button"
        role="tab"
        aria-controls="{{ $cleanName }}"
        aria-selected="{{ $phpActive ? 'true' : 'false' }}"

        {{ $attributes->class($classes) }}
    >
        {{ $slot }}
    </button>
</li>
