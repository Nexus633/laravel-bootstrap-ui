@props([
    'label' => null,
    'divider' => null,
    'icon' => null,
    'danger' => false,
    'action' => null,
    'dispatch' => null,
    'confirm' => false,
    'confirmText' => __('bs::bootstrap-ui.context-menu.confirm'),
    'params' => []
])

@php
    use Illuminate\View\ViewException;

    if(!$divider && !$label){
        throw new ViewException(__('bs::bootstrap-ui.context-menu.ViewException'));
    }

    $jsConfig = [
        'confirm'     => $confirm,
        'confirmText' => $confirmText,
        'action'      => $action,
        'dispatch'    => $dispatch,
        'params'      => $params
    ];
@endphp

@if($divider)
    <x-bs::divider />
@else
    <x-bs::link
        href="#"
        class="dropdown-item d-flex align-items-center cursor-pointer w-100 text-start"
        x-data="bsContextMenuItem({{ json_encode($jsConfig) }})"
        @click.prevent="execute()"
        :no-underline="true"
        :variant="null"
    >
        @if($icon)
            <x-bs::icon name="{{ $icon }}" :variant="$danger ? 'danger' : null" class="me-2"/>
        @endif
        <x-bs::text span :variant="$danger ? 'danger' : null">
            {{ $label }}
        </x-bs::text>
    </x-bs::link>
@endif
