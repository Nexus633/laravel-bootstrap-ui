@props(['items'])

@foreach($items as $item)
    @if(!is_array($item)) @continue @endif

    @php
        // Nur prüfen ob Kinder da sind, für die Rekursion und das Flag
        $hasChildren = !empty($item['children']) && is_array($item['children']);
    @endphp

    <x-bs::tree-view.item
            :label="$item['name'] ?? 'Unbenannt'"
            :icon="$item['icon'] ?? null"
            :variant="$item['variant'] ?? null"
            :size="$item['size'] ?? null"
            :url="$item['url'] ?? null"
            :active="$item['active'] ?? false"
            :open="$item['open'] ?? false"
            :is-folder="$hasChildren"
    >
        @if($hasChildren)
            <x-bs::tree-view.recursive :items="$item['children']" />
        @endif
    </x-bs::tree-view.item>
@endforeach
