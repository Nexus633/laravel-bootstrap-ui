@props([
    'id',
    'name',
    'multiple' => false,
    'accept' => null,
    'variant' => 'primary'
])

<div>
    <x-bs::input
        type="file"
        :id="$id"
        :name="$name"
        :multiple="$multiple"
        :accept="$accept"
        :attributes="$attributes->merge([
            '@change' => 'handleFileSelect',    // Alpine Upload Trigger
            ':disabled' => 'isUploading',       // Deaktivieren beim Laden
        ])"
    />

    <div
        x-show="isUploading"
        style="display: none;"
        x-transition
        class="mt-2"
    >
        <div class="d-flex justify-content-between align-items-center mb-1 px-1">
            <small class="text-body fw-medium">{{ __('bs::bootstrap-ui.file-upload.simple.upload') }}</small>
            <small class="text-body fw-bold" x-text="progress + '%'"></small>
        </div>

        <x-bs::progress height="4px">
            <x-bs::progress.bar
                :variant="$variant"
                value="0"
                x-bind:style="'width: ' + progress + '%'"
            />
        </x-bs::progress>
    </div>
</div>
