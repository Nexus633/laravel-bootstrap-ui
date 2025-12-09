@props([
    'title' => null
])

<div class="modal-header">
    @if($title)
        <h5 class="modal-title">{{ $title }}</h5>
    @endif

    {{ $slot }}

    {{-- Close Button immer rendern --}}
    <x-bs::button.close dismiss="modal" />
</div>
