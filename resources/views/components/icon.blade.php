@props([
    'as' => null,
    'icon' => null
])
@php
    $withInput = $as === 'input';
    if(!$withInput){
        $withInput = $attributes->get('as:input') ?? null;
        $attributes = $attributes->except('as:input');
    }
@endphp


@if($withInput)
    <span {{ $attributes->merge(['class' => 'input-group-text']) }}>
        <i class="bi {{ $icon }}" aria-hidden="true"></i>
    </span>
@else
    <i class="bi {{ $icon }}" aria-hidden="true"></i>
@endif
