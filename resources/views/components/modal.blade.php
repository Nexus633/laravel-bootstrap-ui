@props([
    'id',                       // Pflichtfeld für Service-Steuerung
    'static' => false,          // Backdrop static
    'size' => null,             // sm, lg, xl, fullscreen
    'centered' => false,
    'scrollable' => false,
])

@php
    $wireModel = $attributes->wire('model');
    
    $dialogClasses = ['modal-dialog'];
    if($size) $dialogClasses[] = 'modal-' . $size;
    if($centered) $dialogClasses[] = 'modal-dialog-centered';
    if($scrollable) $dialogClasses[] = 'modal-dialog-scrollable';
@endphp

<div
    class="modal fade"
    id="{{ $id }}"
    tabindex="-1"
    aria-hidden="true"

    {{-- Falls static backdrop gewünscht --}}
    @if($static) data-bs-backdrop="static" data-bs-keyboard="false" @endif

    {{-- Alpine Logic verbinden --}}
    x-data="{ wireModel: @if($wireModel->directive()) @entangle($wireModel->value()) @else null @endif }"
    x-init="
        const modal = new bootstrap.Modal($el);

        // Service Listener
        window.addEventListener('bs-modal-show', e => { if(e.detail.id === '{{ $id }}') modal.show(); });
        window.addEventListener('bs-modal-hide', e => { if(e.detail.id === '{{ $id }}') modal.hide(); });

        // Wire Model Sync
        $watch('wireModel', val => val ? modal.show() : modal.hide());

        // Sync zurück zu Livewire (bei ESC/Close)
        $el.addEventListener('hidden.bs.modal', () => wireModel = false);
        $el.addEventListener('shown.bs.modal', () => wireModel = true);
    "
    {{ $attributes }}
>
    <div class="{{ implode(' ', $dialogClasses) }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
