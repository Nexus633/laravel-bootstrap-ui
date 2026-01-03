@props([
    'id',           // z.B. 'section-1'
    'heading' => null
])

<div id="{{ $id }}" {{ $attributes->class(['mb-4']) }}>
    @if($heading)
        <x-bs::text h4>{{ $heading }}</x-bs::text>
    @endif

    <x-bs::text div class="text-muted">{{ $slot }}</x-bs::text>
</div>
