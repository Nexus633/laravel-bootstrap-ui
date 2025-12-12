<div
    x-show="show"
    x-transition.opacity
    style="display: none; width: 220px;"
    class="position-absolute z-3"
    :class="placement === 'top' ? 'bottom-100 mb-1 start-0' : 'top-100 mt-1 start-0'"
>
    <x-bs::card class="shadow border-0" :body="false">
        <div
            x-ref="list"
            class="d-flex flex-column p-1 overflow-auto custom-scrollbar"
            style="max-height: 300px;"
        >
            <template x-for="slot in timeSlots" :key="slot.value">

                <x-bs::button
                    type="button"
                    align="block"
                    size="sm"
                    variant=""

                    {{-- 'dropdown-item' lassen wir für das Padding/Layout drin --}}
                    class="border-0 py-2 dropdown-item rounded text-center"

                    x-bind:class="{
                        {{-- 1. SELECTED: Dunkelblau --}}
                        'bg-primary text-white active': isSelected(slot.value),

                        {{-- 2. RANGE: Hellblau --}}
                        'bg-primary-subtle text-primary': !isSelected(slot.value) && (isInRange(slot.value) || isHoverRange(slot.value)),

                        {{--
                            3. HOVER (Der Fix):
                            Wenn die Maus genau hier ist (hoverTime === slot.value),
                            aber es weder ausgewählt noch im Range ist -> GRAUER HINTERGRUND.
                            'bg-body-secondary' ist der Standard-Grau-Ton in Bootstrap 5.3.
                        --}}
                        'bg-body-secondary': hoverTime === slot.value && !isSelected(slot.value) && !isInRange(slot.value) && !isHoverRange(slot.value),

                        {{-- 4. NORMAL: Standard Text --}}
                        'text-body': !isSelected(slot.value) && !isInRange(slot.value) && !isHoverRange(slot.value)
                    }"

                    x-bind:disabled="isDisabled(slot.value)"
                    x-bind:style="isDisabled(slot.value) ? 'opacity: 0.25; text-decoration: line-through;' : ''"

                    @mouseover="hoverTime = slot.value"
                    {{-- mouseleave entfernt den Hover wieder, damit nichts hängen bleibt --}}
                    @mouseleave="hoverTime = null"
                    @click="selectTime(slot.value)"
                >
                    <span x-text="slot.label"></span>
                </x-bs::button>

            </template>

            <div x-show="timeSlots.length === 0" class="p-3 text-center text-muted small">
                {{ __('bs::bootstrap-ui.time-picker.dialog.no_time_available') }}
            </div>
        </div>
    </x-bs::card>
</div>
