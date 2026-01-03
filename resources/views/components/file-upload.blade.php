@props([
    'name' => null,
    'label' => __('bs::bootstrap-ui.file-upload.label'),
    'zoneLabel' => __('bs::bootstrap-ui.file-upload.zone.label'),
    'hint' => null,
    'multiple' => false,
    'accept' => null,
    'preview' => true,
    'displayMode' => 'list',
    'model' => null,
    'simple' => false,
    'variant' => 'primary'
])

@php
    use Nexus633\BootstrapUi\Facades\BootstrapUi;
    
    $field = BootstrapUi::make($name);
    $id = $attributes->getOrCreateId('fileUpload-');
    $hasError = $field->hasError();

    $isMultiple = $multiple || $attributes->has('multiple');
    $previewTarget = $model ?? $name;

    if($simple && $displayMode === 'list') {
        $displayMode = 'simple';
    }

    if ($hasError) {
        $attributes = $attributes->merge(['class' => 'is-invalid']);
    }
@endphp

<x-bs::input.wrapper :label="$label" :id="$id" :name="$name" :hint="$hint">
    <div
        x-data='bsFileUpload({
            name: "{{ $name }}",
            id: "{{ $id }}",
            multiple: {{ $isMultiple ? "true" : "false" }},
            isPreview: {{ $preview ? "true" : "false" }}
        })'
    >
        @if($simple)
            <x-bs::file-upload.simple
                :id="$id"
                :name="$name"
                :multiple="$isMultiple"
                :accept="$accept"
                :label="$label"
                :variant="$variant"
                :attributes="$attributes"
            />
        @else
            <x-bs::file-upload.zone
                :id="$id"
                :name="$name"
                :multiple="$isMultiple"
                :accept="$accept"
                :label="$zoneLabel"
                :variant="$variant"
                :attributes="$attributes"
            />
        @endif

        @if($preview)
            <x-bs::file-upload.preview
                :name="$previewTarget"
                :multiple="$isMultiple"
                :displayMode="$displayMode"
            />
        @endif

        @error($name)
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        @if($isMultiple)
            @error($name . '.*')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        @endif
    </div>
</x-bs::input.wrapper>
