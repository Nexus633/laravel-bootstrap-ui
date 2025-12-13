@props([
    'id',
    'name',
    'multiple' => false,
    'accept' => null,
    'label' => __('bs::bootstrap-ui.file-upload.zone.label') ?? 'Datei hier ablegen oder klicken',
    'variant' => 'primary'
])

@php
    if(!$accept) $accept = '*';
@endphp

<div
    class="position-relative text-center p-4 border rounded-3 transition-colors cursor-pointer border-dashed"
    :class="isDropping ? 'border-primary bg-primary-subtle' : 'border-secondary-subtle bg-body-tertiary'"
    style="border-width: 2px !important; min-height: 120px;"

    @dragover.prevent="onDragOver"
    @dragleave.prevent="onDragLeave"
    @drop.prevent="onDrop"
    @click="trigger"
>
    <x-bs::input
        simple
        type="file"
        id="{{ $id }}"
        name="{{ $name }}"
        x-ref="fileInput"
        class="d-none"
        multiple="{{ $multiple }}"
        accept="{{ $accept }}"
        @change="handleFileSelect"
    />

    <div class="d-flex flex-column align-items-center justify-content-center h-100">
        <div class="mb-3 text-muted" :class="{ 'text-primary': isDropping }">
            <x-bs::icon name="cloud-arrow-up" class="fs-1" />
        </div>

        <h6 class="mb-1 fw-bold text-body">
            {{ $label }}
        </h6>
        <small class="text-muted">
            @if($accept)
                {{ __('bs::bootstrap-ui.file-upload.zone.accept') ?? 'Zulässig:' }} {{ $accept }}
            @else
                {{ __('bs::bootstrap-ui.file-upload.zone.accept_all') ?? 'Alle Dateitypen:' }}
            @endif
        </small>
    </div>

    {{-- Loading Overlay --}}
    <div
        x-show="isUploading"
        style="display: none;"
        class="position-absolute top-0 start-0 w-100 h-100 bg-body bg-opacity-75 flex-column align-items-center justify-content-center rounded-3 z-3"
        :class="{ 'd-flex': isUploading }"
    >
        <x-bs::spinner :variant="$variant" class="mb-3" />

        <div class="w-50">
            <x-bs::progress height="6px">
                <x-bs::progress.bar
                    :variant="$variant"
                    value="0"
                    x-bind:style="'width: ' + progress + '%'"
                />
            </x-bs::progress>

            <div class="d-flex justify-content-between mt-1 text-muted small" style="font-size: 0.75rem;">
                {{-- Links: Prozent --}}
                <span x-text="`${progress}%`"></span>

                {{-- Rechts: Zähler (nur bei Multiple) --}}
                <template x-if="multiple && totalFiles > 1">
                    <span x-text="`${currentIndex} / ${totalFiles}`"></span>
                </template>
            </div>
        </div>
    </div>
</div>
