@props([
    'name',
    'label' => null,
    'disabled' => false,
])

<x-bs::dropdown.item
    :data-tab-name="$name"
    x-on:click.prevent="activeTab = '{{ $name }}'"
    x-bind:class="{
        'active': activeTab === '{{ $name }}',
        ['bg-' + tabVariant + ' text-white']: activeTab === '{{ $name }}' && tabType === 'pills' && tabVariant
    }"
    {{ $attributes->merge(['disabled' => $disabled]) }}
>
    {{ $label ?? $name }}
</x-bs::dropdown.item>
