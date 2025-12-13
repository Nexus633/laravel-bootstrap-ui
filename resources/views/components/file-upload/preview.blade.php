@props([
    'name',
    'tooltip' => 'Datei entfernen',
    'multiple' => false,
    'displayMode' => 'list', // 'list', 'grid', oder 'simple' (nur Text)
])

@php
    $files = data_get($this, $name);

    $fileList = [];
    if ($files) {
        $fileList = $multiple && is_array($files) ? $files : [$files];
    }
@endphp

@if(count($fileList) > 0)
    <div class="mt-2"> {{-- Margin etwas verringert für den Simple Mode --}}

        {{-- ================= MODE: LIST & SIMPLE (Text Only) ================= --}}
        @if($displayMode === 'list' || $displayMode === 'simple')
            <div class="d-flex flex-column gap-2">
                @foreach($fileList as $file)
                    @if(method_exists($file, 'temporaryUrl'))

                        <div
                            wire:key="list-{{ $file->getFilename() }}"
                            x-data="{ isDeleted: false }"
                            x-show="!isDeleted"
                            class="border border-secondary-subtle rounded bg-body-tertiary p-2 shadow-sm"
                        >
                            <x-bs::row vAlign="center" g="2">

                                {{-- BILD-SPALTE: Nur anzeigen, wenn NICHT 'simple' Modus --}}
                                @if($displayMode !== 'simple')
                                    <x-bs::row.col size="auto">
                                        <div
                                            wire:ignore
                                            x-data="{ url: '{{ Str::startsWith($file->getMimeType(), 'image/') ? $file->temporaryUrl() : null }}' }"
                                            class="position-relative rounded overflow-hidden border border-secondary-subtle"
                                            style="width: 48px; height: 48px; min-width: 48px;"
                                        >
                                            <template x-if="url">
                                                <img :src="url" class="w-100 h-100 object-fit-cover" alt="{{ $file->getClientOriginalName() }}">
                                            </template>
                                            <template x-if="!url">
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-body-secondary text-secondary">
                                                    <x-bs::icon name="file-earmark" class="fs-4" />
                                                </div>
                                            </template>
                                        </div>
                                    </x-bs::row.col>
                                @endif

                                {{-- TEXT INFO (Dateiname + Größe) --}}
                                <x-bs::row.col>
                                    <div class="text-truncate fw-medium text-body small">
                                        {{-- Icon vor dem Namen im Simple Mode für bessere Optik --}}
                                        @if($displayMode === 'simple')
                                            <x-bs::icon name="file-earmark" class="text-muted me-1" />
                                        @endif
                                        {{ $file->getClientOriginalName() }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ Number::fileSize($file->getSize()) }}
                                    </div>
                                </x-bs::row.col>

                                {{-- DELETE BUTTON --}}
                                <x-bs::row.col size="auto">
                                    @if($multiple)
                                        <x-bs::button
                                            variant="outline-danger"
                                            size="sm"
                                            class="border-0"
                                            icon="x-lg"
                                            @click="isDeleted = true; $wire.removeUpload('{{ $name }}', '{{ $file->getFilename() }}')"
                                        />
                                    @else
                                        <x-bs::button
                                            variant="outline-danger"
                                            size="sm"
                                            class="border-0"
                                            icon="x-lg"
                                            @click="isDeleted = true; $wire.set('{{ $name }}', null)"
                                        />
                                    @endif
                                </x-bs::row.col>
                            </x-bs::row>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- ================= MODE: GRID (Bleibt unverändert) ================= --}}
        @elseif($displayMode === 'grid')
            {{-- ... dein Grid Code (siehe vorherige Antwort) ... --}}
            <x-bs::row g="2">
                @foreach($fileList as $file)
                    @if(method_exists($file, 'temporaryUrl'))

                        <x-bs::row.col
                            size="auto"
                            wire:key="grid-{{ $file->getFilename() }}"
                        >
                            <div
                                x-data="{ isDeleted: false }"
                                x-show="!isDeleted"
                                class="position-relative border border-secondary-subtle rounded overflow-hidden group bg-body-tertiary shadow-sm"
                                style="width: 100px; height: 100px;"
                            >
                                <div
                                    wire:ignore
                                    x-data="{ url: '{{ Str::startsWith($file->getMimeType(), 'image/') ? $file->temporaryUrl() : null }}' }"
                                    class="w-100 h-100"
                                >
                                    <template x-if="url">
                                        <img :src="url" class="w-100 h-100 object-fit-cover" alt="{{ $file->getClientOriginalName() }}">
                                    </template>
                                    <template x-if="!url">
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-body-secondary text-secondary p-1">
                                            <x-bs::icon name="file-earmark" class="fs-2 mb-1" />
                                            <small class="text-truncate w-100 text-center" style="font-size: 0.6rem;">
                                                {{ $file->getClientOriginalName() }}
                                            </small>
                                        </div>
                                    </template>
                                </div>

                                <div class="position-absolute top-0 end-0 p-1">
                                    @if($multiple)
                                        <x-bs::button
                                            variant="danger"
                                            size="sm"
                                            class="p-0 d-flex align-items-center justify-content-center shadow-sm rounded-circle border-0"
                                            style="width: 20px; height: 20px; font-size: 0.75rem;"
                                            wire:click="removeUpload('{{ $name }}', '{{ $file->getFilename() }}')"
                                            @click="isDeleted = true"
                                        >
                                            <x-bs::icon name="x-lg"/>
                                        </x-bs::button>
                                    @else
                                        <x-bs::button
                                            variant="danger"
                                            size="sm"
                                            class="p-0 d-flex align-items-center justify-content-center shadow-sm rounded-circle border-0"
                                            style="width: 20px; height: 20px; font-size: 0.75rem;"
                                            @click="isDeleted = true; $wire.set('{{ $name }}', null)"
                                        >
                                            <x-bs::icon name="x-lg"/>
                                        </x-bs::button>
                                    @endif
                                </div>
                            </div>
                        </x-bs::row.col>
                    @endif
                @endforeach
            </x-bs::row>
        @endif
    </div>
@endif
