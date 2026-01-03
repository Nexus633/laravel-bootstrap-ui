@props([
    'shortcut' => 'ctrl+k',
    'id' => 'command-palette-modal',
    'theme' => null
])

@php
    use Nexus633\BootstrapUi\Facades\CommandPalette;
    $allCommands = CommandPalette::all();
@endphp

{{-- TRIGGER --}}
@if($shortcut)
    <x-bs::shortcut
        :key="$shortcut"
        :js="'window.dispatchEvent(new CustomEvent(\'bs-modal-show\', { detail: { id: \'' . $id . '\' } }))'"
        prevent
    />
@endif

<x-bs::modal :id="$id" size="lg" scrollable centered :data-bs-theme="$theme">
    <div
        x-data="bsCommandPalette(@js($allCommands), @js($id))"
        class="w-100"
        @keydown.window.arrow-down.prevent="onArrowDown()"
        @keydown.window.arrow-up.prevent="onArrowUp()"
        @keydown.window.enter.prevent="selectItem(selectedIndex)"
    >
        <div style="display: none;">{{ $slot }}</div>
        {{-- HEADER --}}
        {{-- FIX: overflow-hidden hier verhindert, dass das Input-Feld breiter als das Modal wird --}}
        <div class="modal-header border-0 p-3 pb-2 align-items-center overflow-hidden">
            <div class="d-flex w-100 align-items-center gap-3 border-bottom border-secondary-subtle pb-2">
                <x-bs::icon name="search" class="text-body-tertiary fs-5 ms-1 flex-shrink-0" />
                <x-bs::input
                    simple
                    name="search"
                    class="border-0 shadow-none py-2 bg-transparent text-body"
                    x-model="search"
                    placeholder="{{ __('Suche...') }}"
                    autocomplete="off"
                    @input="selectedIndex = 0"
                />
                <x-bs::button.close dismiss="modal" />
            </div>
        </div>
        {{-- BODY --}}
        {{-- FIX: overflow-x-hidden Klasse zusätzlich zur Sicherheit --}}
        <x-bs::modal.body class="command-palette p-0 bg-transparent overflow-x-hidden" style="min-height: 50px; max-height: 55vh;">
            {{--
                FIX: Wir nutzen px-2 im Container statt mx-2 auf den Items.
                Das verhindert, dass die Breite gesprengt wird.
            --}}
            <div x-ref="resultsContainer" class="list-group list-group-flush py-2 px-2">
                <template x-for="(items, groupName) in filteredGroups" :key="groupName">
                    <div>
                        {{-- Gruppen Titel --}}
                        <x-bs::text
                            div
                            small
                            bold
                            uppercase
                            variant="body-tertiary"
                            class="px-3 py-1 mt-2 mb-1"
                            style="font-size: 0.7rem; letter-spacing: 0.5px;"
                            x-text="groupName"
                        />
                        {{-- ITEMS --}}
                        <template x-for="item in items" :key="item.id">
                            <button
                                type="button"
                                class="command-palette list-group-item list-group-item-action border-0 d-flex align-items-center gap-3 cp-item rounded-3"
                                :class="{ 'active-item': flatItems[selectedIndex] && flatItems[selectedIndex].id === item.id }"
                                @click="execute(item)"
                                @mouseover="selectedIndex = flatItems.findIndex(i => i.id === item.id)"
                            >
                                <template x-if="item.icon">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 20px;">
                                        <i :class="item.icon" class="text-body-secondary transition-colors"></i>
                                    </div>
                                </template>
                                <div class="flex-grow-1 text-start d-flex flex-column justify-content-center overflow-hidden" style="line-height: 1.2;">
                                    <span class="fw-medium text-body text-truncate" x-text="item.label"></span>
                                    <template x-if="item.description">
                                        <span class="small text-body-secondary opacity-75 mt-1 text-truncate"
                                              x-text="item.description"
                                              style="font-size: 0.8rem;">
                                        </span>
                                    </template>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                    <template x-if="item.keywords">
                                        <span class="badge bg-body-secondary text-body-secondary border border-secondary-subtle fw-normal py-1"
                                              style="font-size: 0.7em;"
                                              x-text="item.keywords">
                                        </span>
                                    </template>
                                    <x-bs::icon
                                        name="arrow-return-left"
                                        variant="body-tertiary"
                                        class="small"
                                        x-show="flatItems[selectedIndex] && flatItems[selectedIndex].id === item.id"
                                    />
                                </div>
                            </button>
                        </template>
                    </div>
                </template>
                <x-bs::empty-state x-show="flatItems.length === 0">
                    Keine Ergebnisse gefunden.
                </x-bs::empty-state>
            </div>
        </x-bs::modal.body>

        {{-- FOOTER --}}
        <x-bs::modal.footer align="start" class="border-top border-secondary-subtle py-2 px-3 bg-transparent overflow-hidden">
            <div class="d-flex gap-3 opacity-50" style="font-size: 0.75rem;">
                 <span class="text-body-secondary d-flex align-items-center gap-1">
                     <x-bs::icon name="arrows-vertical" size="sm"/> <x-bs::text span>Navigieren</x-bs::text>
                 </span>
                <span class="text-body-secondary d-flex align-items-center gap-1">
                     <x-bs::icon name="arrow-return-left" size="sm"/> <x-bs::text span>Auswählen</x-bs::text>
                 </span>
            </div>
        </x-bs::modal.footer>
    </div>
</x-bs::modal>
