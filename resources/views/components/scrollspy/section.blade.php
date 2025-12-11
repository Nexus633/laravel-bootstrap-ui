@props([
    'id',           // z.B. 'section-1'
    'heading' => null
])

<div id="{{ $id }}" {{ $attributes->class(['mb-4']) }}>
    @if($heading)
        <h4>{{ $heading }}</h4>
    @endif

    <div class="text-muted">
        {{ $slot }}
    </div>
</div>
