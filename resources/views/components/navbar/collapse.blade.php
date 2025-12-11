@props([
    'id', // Pflichtfeld! Muss mit Toggler übereinstimmen.
])

<div class="collapse navbar-collapse" id="{{ $id }}" {{ $attributes }}>
    {{ $slot }}
</div>
