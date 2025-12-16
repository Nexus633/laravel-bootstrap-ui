{{-- Wrapper für die Inhalte --}}
<x-bs::card :body="false" class="border-0 shadow-sm">
    <x-bs::card.body x-ref="body">
        {{ $slot }}
    </x-bs::card.body>
</x-bs::card>
