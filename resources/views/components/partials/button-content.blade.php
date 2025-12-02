{{-- Spinner (sichtbar während Loading) --}}
@if($loading)
    <span
            class="spinner-border spinner-border-sm me-1"
            role="status"
            aria-hidden="true"
            wire:loading
            wire:target="{{ $loading }}"
    ></span>
@endif

{{-- Icon (wird ausgeblendet beim Laden, damit der Button nicht breiter springt) --}}
@if($icon)
    <i class="{{ $icon }} me-1" @if($loading) wire:loading.remove wire:target="{{ $loading }}" @endif></i>
@endif

{{-- Slot Inhalt --}}
<span>{{ $slot }}</span>
