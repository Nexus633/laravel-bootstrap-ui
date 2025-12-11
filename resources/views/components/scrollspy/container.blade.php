@props([
    'target',            // Die ID der Navigation (Pflicht!)
    'height' => '500px', // Höhe des scrollbaren Bereichs
    'smooth' => true,    // Weiches Scrollen
])

<div
    data-bs-spy="scroll"
    data-bs-target="#{{ $target }}"
    data-bs-smooth-scroll="{{ $smooth ? 'true' : 'false' }}"
    tabindex="0"
    {{ $attributes->class(['scrollspy-example', 'bg-body-tertiary', 'p-3', 'rounded-2']) }}
    style="height: {{ $height }}; overflow-y: scroll; position: relative;"
>
    {{ $slot }}
</div>
