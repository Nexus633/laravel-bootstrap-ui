@props([
    'variant' => null,
    'sticky' => false,
    
    // Sorting Props
    'sortable' => false,
    'sorted' => false,
    'direction' => 'asc'
])

@php
    // 1. Wir sammeln alle Klassen in einem Array
    $classesArray = [
        $variant ? 'table-' . $variant : null,
        $sticky ? 'table-sticky-cell' : null,
        $sortable ? 'cursor-pointer user-select-none' : null,
    ];

    // 2. Leere Einträge entfernen
    $classesArray = array_filter($classesArray);

    // 3. String bauen (oder null, damit das class-Attribut ganz wegfällt wenn leer)
    $classString = empty($classesArray) ? null : implode(' ', $classesArray);
@endphp

<th
        {{-- Hier übergeben wir den kombinierten String an merge --}}
        {{ $attributes->merge(['class' => $classString]) }}

        {{-- Sticky Marker für unser Alpine JS --}}
        @if($sticky) data-sticky="true" @endif

        {{-- Layout Fix für Sortierung: Mindestbreite & Button Role --}}
        @if($sortable) style="min-width: 80px;" role="button" @endif
>
    <div class="d-flex align-items-center justify-content-between gap-1">

        {{-- Inhalt (Text umbrechen verhindern) --}}
        <span class="text-nowrap">{{ $slot }}</span>

        {{-- Sortier-Icon --}}
        @if($sortable)
            <span class="d-inline-flex flex-shrink-0 text-muted ms-2" style="opacity: {{ $sorted ? '1' : '0.3' }}">
                @if($sorted && $direction === 'asc')
                    {{-- Icon: Aufsteigend --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/></svg>
                @elseif($sorted && $direction === 'desc')
                    {{-- Icon: Absteigend --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>
                @else
                    {{-- Icon: Neutral --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 0-.5.5v11.793l-3.146-3.147a.5.5 0 0 0-.708.708l4 4a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0-.708-.708L4 13.293V1.5a.5.5 0 0 0-.5-.5z"/></svg>
                @endif
            </span>
        @endif
    </div>
</th>
