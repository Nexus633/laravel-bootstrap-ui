@props([
    'label',
    'icon' => null,
])

<div
    x-data="{
        isChildActive: false,
        updateActiveState() {
            const items = Array.from(this.$el.querySelectorAll('[data-tab-name]'));
            const names = items.map(el => el.getAttribute('data-tab-name'));
            this.isChildActive = names.includes(activeTab);
        }
    }"
    x-init="
        updateActiveState();
        $watch('activeTab', () => updateActiveState());
    "
>
    <x-bs::dropdown
        {{ $attributes->merge(compact('label', 'icon')) }}
        nav
        x-bind:class="{ 'active': isChildActive }"
    >
        {{ $slot }}
    </x-bs::dropdown>
</div>
