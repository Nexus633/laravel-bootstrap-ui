@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'multiple' => false,
    'hint' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,
    'simple' => false,
    'disabled' => null,
    'readonly' => null,
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    use Nexus633\BootstrapUi\Facades\Icon;

    $field = BootstrapUi::make($name);

    $isFloating = $attributes->pluck('label:floating');
    $iconPrepend = Icon::toClass($attributes->pluck('icon:prepend'));
    $iconAppend = Icon::toClass($attributes->pluck('icon:append'));

    $id = $attributes->getOrCreateId('input-');
    $hasError = $field->hasError();

    $field->addClass('form-control')
          ->addClassWhen($hasError, 'is-invalid')
          ->addClassWhen($size, 'form-control-' . $size);

    $hasGroup = ($prepend || $append || $iconPrepend || $iconAppend);

    if ($multiple && $type !== 'file') {
        $multiple = false;
    }
@endphp

@if($simple)
    <input
        id="{{ $id }}"
        type="{{ $type }}"
        @if($name) name="{{ $name }}" @endif
        @if($multiple) multiple @endif
        {{ $attributes->class($field->getClasses()) }}
    />
@else
    <x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint" :floating="$isFloating">
        @if($hasGroup)
            <x-bs::input.group :size="$size">
                @if($prepend || $iconPrepend)
                    <x-bs::input-group.text>
                        @if($iconPrepend) <x-bs::icon :name="$iconPrepend" /> @else {!! $prepend !!} @endif
                    </x-bs::input-group.text>
                @endif

                @if($isFloating)
                    <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                        <input id="{{ $id }}" type="{{ $type }}" @if($name) name="{{ $name }}" @endif placeholder="{{ $label }}" {{ $attributes->class($field->getClasses()) }} />
                        <x-bs::text label for="{{ $id }}">{{ $label }}</x-bs::text>
                    </div>
                @else
                    <input id="{{ $id }}" type="{{ $type }}" @if($name) name="{{ $name }}" @endif {{ $attributes->class($field->getClasses()) }} />
                @endif

                @if($append || $iconAppend)
                    <x-bs::input-group.text>
                        @if($iconAppend) <x-bs::icon :name="$iconAppend" /> @else {!! $append !!} @endif
                    </x-bs::input-group.text>
                @endif
            </x-bs::input.group>
        @elseif($isFloating)
            <div class="form-floating {{ $hasError ? 'is-invalid' : '' }}">
                <input id="{{ $id }}" type="{{ $type }}" @if($name) name="{{ $name }}" @endif placeholder="{{ $label }}" {{ $attributes->class($field->getClasses()) }} />
                <x-bs::text label for="{{ $id }}">{{ $label }}</x-bs::text>
            </div>
        @else
            <input id="{{ $id }}" type="{{ $type }}" @if($name) name="{{ $name }}" @endif {{ $attributes->class($field->getClasses()) }} />
        @endif
    </x-bs::input.wrapper>
@endif
