@props(['label' => null])

<div {{ $attributes->merge(['class' => 'tree-view-container']) }}>
    @if($label)
        <h6 class="mb-3 text-uppercase text-body-secondary fs-7 fw-bold ls-wide">
            {{ $label }}
        </h6>
    @endif

    <ul class="tree-view-list">
        {{ $slot }}
    </ul>
</div>
