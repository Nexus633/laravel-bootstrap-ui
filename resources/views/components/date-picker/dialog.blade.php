<div
    x-show="show"
    x-transition.opacity
    style="display: none;"
    class="position-absolute start-0 z-3"
    :style="double ? 'width: max-content; max-width: 660px;' : 'width: 320px;'"
    :class="placement === 'top' ? 'bottom-100 mb-1' : 'top-100 mt-1'"
>
    <x-bs::card class="shadow border-0" :body="false">
        <x-bs::card.body class="p-2">
            <div class="d-flex flex-wrap gap-4">
                <template x-for="(cal, calIndex) in calendars" :key="calIndex">
                    <div class="flex-grow-1" style="min-width: 280px;">
                        {{-- HEADER --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div style="width: 32px;">
                                <x-bs::button
                                    x-show="calIndex === 0"
                                    variant="link" class="text-body p-0 text-decoration-none" icon="chevron-left"
                                    @click="prevPage()"
                                />
                            </div>

                            <x-bs::button
                                variant="link" size="sm" class="text-decoration-none text-body fw-bold user-select-none"
                                @click="!double && toggleView()"
                                x-bind:class="{ 'cursor-default': double }"
                            >
                                <span x-text="cal.monthName + ' ' + cal.year"></span>
                            </x-bs::button>

                            <div style="width: 32px;" class="text-end">
                                <x-bs::button
                                    x-show="calIndex === (calendars.length - 1)"
                                    variant="link" class="text-body p-0 text-decoration-none" icon="chevron-right"
                                    @click="nextPage()"
                                />
                            </div>
                        </div>
                        {{-- WOCHENTAGE --}}
                        <div class="d-grid gap-1 text-center mb-1" style="grid-template-columns: repeat(7, 1fr);">
                            <template x-for="day in dayNames">
                                <small class="text-muted fw-semibold" style="font-size: 0.75rem;" x-text="day"></small>
                            </template>
                        </div>

                        {{-- TAGE GRID --}}
                        <div class="d-grid gap-1" style="grid-template-columns: repeat(7, 1fr);">
                            <template x-for="b in cal.blankDays"><div></div></template>

                            <template x-for="date in cal.days" :key="date">
                                <x-bs::button
                                    size="sm"
                                    type="button"
                                    class="p-0 d-flex align-items-center justify-content-center rounded"
                                    style="height: 36px; width: 100%;"
                                    variant="" {{-- WICHTIG: Reset --}}

                                    {{--
                                        LOGIK WIE IM TIME-PICKER:
                                        1. Selected: Blau
                                        2. Range: Hellblau
                                        3. Hover: Grau (bg-body-secondary) via Alpine Variable
                                        4. Normal: Text-Body
                                    --}}
                                    x-bind:class="{
                                        'bg-primary text-white': isSelected(date, cal.month, cal.year),
                                        'bg-primary-subtle text-primary': !isSelected(date, cal.month, cal.year) && (isInRange(date, cal.month, cal.year) || isHoverRange(date, cal.month, cal.year)),
                                        {{-- HOVER LOGIK --}}
                                        'bg-body-secondary': hoverDate === getDateStr(date, cal.month, cal.year) && !isSelected(date, cal.month, cal.year) && !isInRange(date, cal.month, cal.year) && !isHoverRange(date, cal.month, cal.year),
                                        {{-- HEUTE LOGIK (Rahmen statt Hintergrund, damit Hover sichtbar bleibt) --}}
                                        'border border-primary': isToday(date, cal.month, cal.year) && !isSelected(date, cal.month, cal.year),
                                        'text-body': !isSelected(date, cal.month, cal.year) && !isInRange(date, cal.month, cal.year) && !isHoverRange(date, cal.month, cal.year)
                                    }"
                                    x-bind:disabled="isDisabled(date, cal.month, cal.year)"
                                    x-bind:style="isDisabled(date, cal.month, cal.year) ? 'opacity: 0.25; text-decoration: line-through; pointer-events: none; border-color: transparent;' : ''"

                                    @mouseover="hoverDate = getDateStr(date, cal.month, cal.year)"
                                    @mouseleave="hoverDate = null"
                                    @click="selectDate(date, cal.month, cal.year)"
                                >
                                    <span x-text="date"></span>
                                </x-bs::button>
                            </template>
                        </div>
                    </div>
                    <div x-show="double && calIndex === 0" class="d-none d-sm-block border-end"></div>
                </template>
            </div>

            {{-- JAHRES-ANSICHT (Ebenfalls angepasst) --}}
            <div x-show="view === 'years' && !double" style="display: none;">
                <div class="d-grid gap-2" style="grid-template-columns: repeat(4, 1fr);">
                    <template x-for="y in yearsList" :key="y">
                        <x-bs::button
                            size="sm"
                            type="button"
                            class="py-2 d-flex align-items-center justify-content-center rounded"
                            variant=""
                            x-bind:class="{
                                'bg-primary text-white': cursorYear === y,
                                'bg-body-secondary': hoverDate === y && cursorYear !== y, {{-- Wir nutzen hoverDate Variable recyclend für Jahre --}}
                                'text-body': cursorYear !== y
                            }"
                            @mouseover="hoverDate = y"
                            @mouseleave="hoverDate = null"
                            @click="selectYear(y)"
                        >
                            <span x-text="y"></span>
                        </x-bs::button>
                    </template>
                </div>
            </div>
            <div class="mt-2 text-center border-top pt-2">
                <x-bs::button variant="link" size="sm" class="text-decoration-none" @click="gotoToday()">{{ __('bs::bootstrap-ui.date-picker.dialog.today') }}</x-bs::button>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>
