@props([
    'name',
    'label' => null,
    'icon' => null,
    'disabled' => false,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $ui = BootstrapUi::make()->addClass('nav-link')->addClassWhen($disabled, 'disabled');
    $iconClass = Icon::toClass($icon);
@endphp

<li class="nav-item" role="presentation">
    <button
        {{ $attributes->class($ui->getClasses()) }}
        :class="{
            'active': activeTab === '{{ $name }}',
            ['text-bg-' + tabVariant]: activeTab === '{{ $name }}' && tabType === 'pills' && tabVariant,
            ['text-' + tabVariant]: activeTab === '{{ $name }}' && tabType === 'tabs' && tabVariant,
        }"
        @click="activeTab = '{{ $name }}'"
        :id="parentId + '-{{ $name }}-tab'"
        :data-bs-target="'#' + parentId + '-{{ $name }}-pane'"
        :aria-controls="parentId + '-{{ $name }}-pane'"
        :aria-selected="activeTab === '{{ $name }}'"
        data-bs-toggle="tab"
        type="button"
        role="tab"
        @if($disabled) tabindex="-1" @endif
    >
        @if($iconClass)
            <i class="{{ $iconClass }} me-1"></i>
        @endif
        {{ $label ?? $name }}
    </button>
</li>

