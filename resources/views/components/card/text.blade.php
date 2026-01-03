@props([
    'content' => null
])
@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    $field = BootstrapUi::make();
    $field->addClass('card-text');
@endphp
<p class="{{ $attributes->class($field->getClasses()) }}">@if($content) {{ $content }} @else {{ $slot }} @endif</p>
