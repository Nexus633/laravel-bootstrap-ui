@props([
    'id',
    'name', // Name ist jetzt Pflicht für die Inputs
    'range',
    'separate',
    'labelStart',
    'labelEnd',
    'placeholder',
    'iconPrepend',
    'iconAppend',
    'separator',

    // nur wenn separate aktiv ist
    'iconPrependStart' => null,
    'iconAppendStart' => null,
    'iconPrependEnd' => null,
    'iconAppendEnd' => null,
])

@if($range && $separate)
    {{-- A) Separate Mode (Grid) --}}
    <x-bs::row g="2">
        <x-bs::row.col size="6">
            <x-bs::input
                :id="$id . '-start'"
                :name="$name . '_start'" {{-- Eigener Name --}}
                :label="$labelStart ?? 'Start'"
                :placeholder="$placeholder ?? __('bs::bootstrap-ui.time-picker.inputs.from')"

                {{-- Icons --}}
                :icon:prepend="$iconPrependStart"
                :icon:append="$iconAppendStart"

                x-model="startDisplay"
                @click="toggle()"
                readonly
                style="cursor: pointer;"
                class="bg-transparent"
                autocomplete="off"
            />
        </x-bs::row.col>
        <x-bs::row.col size="6">
            <x-bs::input
                :id="$id . '-end'"
                :name="$name . '_end'" {{-- Eigener Name --}}
                :label="$labelEnd ?? 'Ende'"
                :placeholder="$placeholder ?? __('bs::bootstrap-ui.time-picker.inputs.to')"

                {{-- Icons --}}
                :icon:prepend="$iconPrependEnd"
                :icon:append="$iconAppendEnd"

                x-model="endDisplay"
                @click="toggle()"
                readonly
                style="cursor: pointer;"
                class="bg-transparent"
                autocomplete="off"
            />
        </x-bs::row.col>
    </x-bs::row>
@elseif($range)
    {{-- B) Group Mode (Input Group) --}}
    <x-bs::input.group>
        @if($iconPrepend)
            <x-bs::input-group.text class="bg-body-tertiary text-muted">
                <x-bs::icon :name="$iconPrepend" />
            </x-bs::input-group.text>
        @endif

        <x-bs::input
            :id="$id . '-start'"
            :name="$name . '_start'"
            :placeholder="$placeholder ?? __('bs::bootstrap-ui.time-picker.inputs.from')"
            x-model="startDisplay"
            @click="toggle()"
            readonly
            simple
            style="cursor: pointer;"
            class="bg-transparent text-center"
            autocomplete="off"
        />

        {{-- Separator --}}
        <x-bs::input-group.text class="bg-body-tertiary border-start-0 border-end-0 px-2 text-muted">
            <x-bs::icon :name="$separator" />
        </x-bs::input-group.text>

        <x-bs::input
            :id="$id . '-end'"
            :name="$name . '_end'"
            :placeholder="$placeholder ?? __('bs::bootstrap-ui.time-picker.inputs.to')"
            x-model="endDisplay"
            @click="toggle()"
            readonly
            simple
            style="cursor: pointer;"
            class="bg-transparent text-center"
            autocomplete="off"
        />

        @if($iconAppend)
            <x-bs::input-group.text class="bg-body-tertiary text-muted">
                <x-bs::icon :name="$iconAppend" />
            </x-bs::input-group.text>
        @endif
    </x-bs::input.group>

@else
    {{-- C) Single Mode --}}
    <x-bs::input
        :id="$id"
        :name="$name . '_input'"
        :icon:prepend="$iconPrepend"
        :icon:append="$iconAppend"
        :placeholder="$placeholder ?? __('bs::bootstrap-ui.time-picker.inputs.select_time')"
        x-model="formattedValue"
        @click="toggle()"
        readonly
        style="cursor: pointer;"
        class="bg-transparent"
        autocomplete="off"
    />
@endif
