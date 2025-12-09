@props([
    'label',
    'id' => null,
])

@php
    $dropdownId = $id ?? 'dropdown-' . md5(uniqid());

    // DER TRICK:
    // Wir wandeln den Slot in einen String um und suchen nach unserer Markierung.
    // Das ist super schnell und passiert serverseitig.
    $slotHtml = (string) $slot;
    $hasActiveChild = str_contains($slotHtml, 'data-active-item="true"');

    $classes = ['nav-link', 'dropdown-toggle'];
    // Wenn gefunden -> Parent ist aktiv!
    if ($hasActiveChild) $classes[] = 'active';
@endphp

<li
    class="nav-item dropdown"
    {{--
        Alpine Logik (Client-Side Only):
        Dient nur noch dazu, beim Klick sofort umzuschalten,
        bevor der Server antwortet.
    --}}
    x-data="{
        isActive: {{ $hasActiveChild ? 'true' : 'false' }},
        checkChildren() {
            const childValues = Array.from(this.$el.querySelectorAll('[data-nav-value]'))
                                     .map(el => el.getAttribute('data-nav-value'));
            this.isActive = childValues.includes(activeTab);
        }
    }"
    x-effect="checkChildren()"
>
    <a
        href="#"
        {{-- Alpine Binding (aktualisiert sich bei Klicks) --}}
        :class="{ 'active': isActive }"

        {{-- PHP Klassen (Initialer Render ohne Flackern) --}}
        {{ $attributes->class($classes) }}

        id="{{ $dropdownId }}"
        role="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        {{ $label }}
    </a>

    <ul class="dropdown-menu" aria-labelledby="{{ $dropdownId }}">
        {{ $slot }}
    </ul>
</li>
