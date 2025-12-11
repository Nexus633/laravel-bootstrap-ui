@props([
    'id', // Pflicht: Muss mit dem 'target' vom Container übereinstimmen
])

<div id="{{ $id }}" {{ $attributes->class(['list-group']) }}>
    {{ $slot }}
</div>
