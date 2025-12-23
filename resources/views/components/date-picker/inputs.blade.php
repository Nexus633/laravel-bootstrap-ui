@props([
    'id',
    'name',
    'placeholder',
    'range' => false,
    'separate' => false,
    'labelStart' => null,
    'labelEnd' => null,
    'transparent' => false,


    // Icons empfangen
    'iconPrepend' => null,
    'iconAppend' => null,

    // nur wenn separate aktiv ist
    'iconPrependStart' => null,
    'iconAppendStart' => null,
    'iconPrependEnd' => null,
    'iconAppendEnd' => null,
])

@if($range && $separate)
    {{--
        OPTION A: SEPARATE MODE (Grid)
        Zwei Inputs nebeneinander. Beide erhalten die Icons.
    --}}
    <x-bs::row g="2">
        <x-bs::row.col size="6">
            <x-bs::input
                :id="$id . '-start'"
                :name="$name . '_start_display'"
                :label="$labelStart ?? 'Start'"
                :placeholder="__('bs::bootstrap-ui.date-picker.inputs.from')"

                {{-- Icons --}}
                :icon:prepend="$iconPrependStart"
                :icon:append="$iconAppendStart"

                x-model="startDisplay"
                @click="toggle()"
                readonly
                style="cursor: pointer;"
                autocomplete="off"
                {{ $attributes }}
            />
        </x-bs::row.col>
        <x-bs::row.col size="6">
            <x-bs::input
                :id="$id . '-end'"
                :name="$name . '_end_display'"
                :label="$labelEnd ?? 'Ende'"
                :placeholder="__('bs::bootstrap-ui.date-picker.inputs.to')"

                {{-- Icons --}}
                :icon:prepend="$iconPrependEnd"
                :icon:append="$iconAppendEnd"

                x-model="endDisplay"
                @click="toggle()"
                readonly
                style="cursor: pointer;"
                autocomplete="off"
                {{ $attributes }}
            />
        </x-bs::row.col>
    </x-bs::row>
@else
    {{--
        OPTION B: STANDARD MODE (Single & Range Combined)
        Ein einzelnes Feld.
    --}}
    <x-bs::input
        :id="$id"
        :name="$name . '_display'"
        :placeholder="$placeholder  ?? __('bs::bootstrap-ui.date-picker.inputs.select_date')"

        {{-- Icons --}}
        :icon:prepend="$iconPrepend"
        :icon:append="$iconAppend"

        x-model="formattedDate"
        @click="toggle()"
        readonly
        style="cursor: pointer;"
        autocomplete="off"
        {{ $attributes }}
    />
@endif
