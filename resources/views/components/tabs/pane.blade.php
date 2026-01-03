@props([
    'name',
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $ui = BootstrapUi::make();
    $ui->addClass('tab-pane', 'fade')
       ->addData('role', 'tabpanel')
       ->addData('tabindex', '0');
@endphp

<div
    {{ $attributes->class($ui->getClasses())->merge($ui->getDataAttributes()) }}
    :id="parentId + '-{{ $name }}-pane'"
    :class="{ 'show active': activeTab === '{{ $name }}' }"
    :aria-labelledby="parentId + '-{{ $name }}-tab'"
>
    {{ $slot }}
</div>
