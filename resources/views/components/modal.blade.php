@props([
    'id',                       // Pflichtfeld für Service-Steuerung
    'static' => false,          // Backdrop static
    'size' => null,             // sm, lg, xl, fullscreen
    'centered' => false,
    'scrollable' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $wireModel = $attributes->wire('model');

    $field = BootstrapUi::make();
    $field->addClass('modal-dialog')
          ->addClassWhen($size, 'modal-' . $size)
          ->addClassWhen($centered, 'modal-dialog-centered')
          ->addClassWhen($scrollable, 'modal-dialog-scrollable');
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
    <div class="{{ $field->getClasses() }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
