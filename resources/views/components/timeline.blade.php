@props([
    'cutoff' => 0,
])

@php
    // Wir generieren eine zufällige ID für DIESE Instanz.
    // Das ist wichtig, damit das CSS nicht andere Timelines auf der Seite zerschießt.
    $uuid = 'timeline-' . \Illuminate\Support\Str::random(8);
    $cutoff = (int) $cutoff;
@endphp

<div
    id="{{ $uuid }}"
    x-data="bsTimeline({
        cutoff: {{ $cutoff }}
    })"
    class="position-relative ms-3 my-2"
>
    {{--
        Scoped CSS:
        Wir nutzen die eindeutige ID ($uuid), um das CSS hart an diese Timeline zu binden.
        Der Browser blendet alles ab Element X sofort aus.
    --}}
    @if($cutoff > 0)
        <style>
            #{{ $uuid }} .timeline-list:not(.is-expanded) > div:nth-child(n+{{ $cutoff + 1 }}) {
                display: none !important;
            }
        </style>
    @endif

    {{-- ITEM LISTE --}}
    <div
        x-ref="listContainer"
        class="timeline-list border-start border-2 border-body-secondary"
        :class="expanded && 'is-expanded'"
    >
        {{ $slot }}
    </div>

    {{-- BUTTONS (Nur rendern, wenn Cutoff aktiv ist) --}}
    @if($cutoff > 0)
        <div x-cloak x-show="shouldShowExpand" class="ps-4 mt-2">
            <x-bs::button size="sm" variant="outline-secondary" class="rounded-pill px-3" @click="expanded = true">
                <x-bs::icon name="chevron-down" class="me-1"/>
                <span x-text="remainingCount + ' {{ __('bs::bootstrap-ui.timeline.show_more') }}'"></span>
            </x-bs::button>
        </div>

        <div x-cloak x-show="shouldShowCollapse" class="ps-4 mt-2">
            <x-bs::button size="sm" variant="link" class="text-body-secondary text-decoration-none px-0" @click="expanded = false">
                {{ __('bs::bootstrap-ui.timeline.show_less') }}
            </x-bs::button>
        </div>
    @endif

    {{-- FOOTER / PAGINATION --}}
    @if(isset($footer))
        <div class="ps-4 mt-3">
            {{ $footer }}
        </div>
    @endif
</div>
