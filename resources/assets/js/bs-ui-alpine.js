document.addEventListener('alpine:init', () => {
    registerAlpineFunctions();
    registerAlpineDirective();
});

function registerAlpineFunctions() {
    Alpine.data('bsUiState', (id, type = 'tab', usePersist = false) => ({

        // Wir speichern jetzt ein Array, keine einzelne ID mehr
        activeItems: [],
        storageKey: 'bs-ui-' + id,

        init() {
            this.loadState();

            // State wiederherstellen (sobald DOM bereit)
            this.$nextTick(() => this.restoreState());

            // Listener registrieren
            this.registerListeners();

            // Livewire Hook (für Re-Renders)
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('commit', ({ _component, succeed }) => {
                    succeed(() => {
                        this.$nextTick(() => this.restoreState());
                    });
                });
            }
        },

        registerListeners() {
            if (type === 'tab') {
                this.$el.addEventListener('shown.bs.tab', (e) => {
                    // Tabs sind exklusiv -> Array komplett ersetzen
                    const target = e.target.getAttribute('data-bs-target');
                    this.saveState([target]);
                });
            }
            else if (type === 'accordion') {
                // HINZUFÜGEN beim Öffnen
                this.$el.addEventListener('shown.bs.collapse', (e) => {
                    // Sicherstellen, dass Event vom eigenen Accordion kommt (nested Accordions!)
                    if (e.target.closest('.accordion') === this.$el) {
                        this.addItem(e.target.id);
                    }
                });

                // ENTFERNEN beim Schließen
                this.$el.addEventListener('hidden.bs.collapse', (e) => {
                    if (e.target.closest('.accordion') === this.$el) {
                        this.removeItem(e.target.id);
                    }
                });
            }
        },

        addItem(id) {
            if (!this.activeItems.includes(id)) {
                this.activeItems.push(id);
                this.saveState(this.activeItems);
            }
        },

        removeItem(id) {
            this.activeItems = this.activeItems.filter(item => item !== id);
            this.saveState(this.activeItems);
        },

        restoreState() {
            // Wenn leer, nichts tun
            if (!this.activeItems || this.activeItems.length === 0) return;

            // DOM-Element muss existieren
            if (!document.body.contains(this.$el)) return;

            if (type === 'tab') {
                // Bei Tabs nehmen wir einfach das erste Element im Array
                const activeId = this.activeItems[0];
                const trigger = this.$el.querySelector(`[data-bs-target="${activeId}"]`);

                if (trigger && !trigger.classList.contains('active')) {
                    const tab = bootstrap.Tab.getOrCreateInstance(trigger);
                    tab.show();
                }
            }
            else if (type === 'accordion') {
                // Loop durch ALLE gespeicherten IDs und öffne sie
                this.activeItems.forEach(itemId => {
                    const content = document.getElementById(itemId);
                    // Nur öffnen, wenn existiert und noch nicht offen
                    if (content && !content.classList.contains('show')) {
                        const collapse = bootstrap.Collapse.getOrCreateInstance(content, { toggle: false });
                        collapse.show();
                    }
                });
            }
        },

        saveState(items) {
            this.activeItems = items;
            const storage = usePersist ? localStorage : sessionStorage;

            if (items.length > 0) {
                // Array als JSON speichern
                storage.setItem(this.storageKey, JSON.stringify(items));
            } else {
                storage.removeItem(this.storageKey);
            }
        },

        loadState() {
            const storage = usePersist ? localStorage : sessionStorage;
            const stored = storage.getItem(this.storageKey);

            if (stored) {
                try {
                    // JSON parsen
                    this.activeItems = JSON.parse(stored);

                    // Fallback, falls doch mal kein Array drin steht (Migration alter Daten)
                    if (!Array.isArray(this.activeItems)) {
                        this.activeItems = [stored];
                    }
                } catch (e) {
                    this.activeItems = [];
                }
            } else {
                this.activeItems = [];
            }
        }
    }));

    Alpine.data('bsTableSticky', () => ({
        init() {
            this.updateSticky();
            window.addEventListener('resize', () => this.updateSticky());

            if (typeof Livewire !== 'undefined') {
                Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => this.$nextTick(() => this.updateSticky()));
                });
            }
        },

        updateSticky() {
            const table = this.$el;
            const rows = table.querySelectorAll('tr');
            if (rows.length === 0) return;

            // 1. Header analysieren - welche SPALTEN sind sticky?
            const stickyIndices = new Set();
            const headerCells = table.querySelectorAll('thead tr:first-child th');
            let colIdx = 0;

            headerCells.forEach((cell) => {
                const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);

                // ✅ Nur Zellen OHNE colspan werden sticky
                if (colspan === 1 && cell.hasAttribute('data-sticky')) {
                    stickyIndices.add(colIdx);
                }

                colIdx += colspan;
            });

            // 2. Alle Zeilen verarbeiten
            rows.forEach((row) => {
                const cells = Array.from(row.children);
                const parentTag = row.parentElement.tagName;

                // Spaltenbreiten Map erstellen
                const columnWidths = new Map();
                let realColumnIndex = 0;

                cells.forEach((cell) => {
                    const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);
                    const cellWidth = cell.getBoundingClientRect().width;
                    const widthPerColumn = cellWidth / colspan;

                    for (let i = 0; i < colspan; i++) {
                        columnWidths.set(realColumnIndex + i, widthPerColumn);
                    }
                    realColumnIndex += colspan;
                });

                // Sticky Position setzen
                realColumnIndex = 0;

                cells.forEach((cell) => {
                    const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);
                    const isExplicitSticky = cell.hasAttribute('data-sticky');

                    // ✅ REGEL: Ignoriere alle Zellen mit colspan > 1
                    if (colspan > 1) {
                        // Reset: Kein Sticky für colspan-Zellen
                        cell.style.left = '';
                        cell.style.position = '';
                        cell.style.zIndex = '';
                        cell.classList.remove('table-sticky-cell');

                        realColumnIndex += colspan;
                        return; // Nächste Zelle
                    }

                    // Nur für colspan=1: Prüfe ob sticky
                    const shouldBeSticky = isExplicitSticky || stickyIndices.has(realColumnIndex);

                    if (shouldBeSticky) {
                        // Berechne left-Offset: Summe aller sticky Spalten VOR dieser Zelle
                        let leftOffset = 0;
                        for (let col = 0; col < realColumnIndex; col++) {
                            if (stickyIndices.has(col)) {
                                leftOffset += (columnWidths.get(col) || 0);
                            }
                        }

                        cell.style.left = leftOffset + 'px';
                        cell.style.position = 'sticky';
                        cell.classList.add('table-sticky-cell');

                        // Z-Index nach Parent
                        if (parentTag === 'THEAD') cell.style.zIndex = '5';
                        else if (parentTag === 'TFOOT') cell.style.zIndex = '3';
                        else cell.style.zIndex = '2';

                    } else {
                        // Reset
                        cell.style.left = '';
                        cell.style.position = '';
                        cell.style.zIndex = '';
                        cell.classList.remove('table-sticky-cell');
                    }

                    realColumnIndex += colspan;
                });
            });
        }
    }));

    Alpine.data('bsDatePicker', ({ model, min, max, mode, disable, months, days, double }) => ({
            show: false,
            value: model,
            mode: mode,
            double: double,
            disabledDates: disable ?? [],

            rangeFrom: null,
            rangeTo: null,
            hoverDate: null,

            view: 'days',
            placement: 'bottom',
            cursorMonth: '',
            cursorYear: '',
            calendars: [],
            yearsList: [],
            monthNames: months,
            dayNames: days,

            init() {
                let today = new Date();

                if (this.mode === 'range') {
                    if (this.value && typeof this.value === 'object') {
                        this.rangeFrom = this.value.start;
                        this.rangeTo = this.value.end;
                    }
                    this.initDate(this.rangeFrom || today);
                } else {
                    this.initDate(this.value || today);
                }

                this.$watch('value', val => {
                    if (this.mode === 'single') this.initDate(val || new Date());
                });
            },

            initDate(val) {
                let d = new Date(val);
                if (isNaN(d.getTime())) d = new Date();
                this.cursorMonth = d.getMonth();
                this.cursorYear = d.getFullYear();
                this.updateCalendars();
            },

            updateCalendars() {
                this.calendars = [];
                let count = this.double ? 2 : 1;

                for (let i = 0; i < count; i++) {
                    let m = this.cursorMonth + i;
                    let y = this.cursorYear;
                    if (m > 11) { m -= 12; y++; }

                    this.calendars.push({
                        month: m,
                        year: y,
                        monthName: this.monthNames[m],
                        ...this.calculateDays(m, y)
                    });
                }
            },

            calculateDays(month, year) {
                let firstDayOfMonth = new Date(year, month, 1).getDay();
                let jsDayToMonStart = (day) => (day === 0 ? 6 : day - 1);
                let firstDayIndex = jsDayToMonStart(firstDayOfMonth);
                let daysCount = new Date(year, month + 1, 0).getDate();

                return {
                    blankDays: new Array(firstDayIndex),
                    days: new Array(daysCount).fill().map((_, i) => i + 1)
                };
            },

            toggle() {
                this.show = !this.show;
                if (this.show) {
                    this.updatePlacement();
                    this.view = 'days';
                    if (this.mode === 'single' && !this.value) this.initDate(new Date());
                    if (this.mode === 'range' && !this.rangeFrom) this.initDate(new Date());
                } else {
                    this.hoverDate = null;
                }
            },
            close() { this.show = false; this.hoverDate = null; },

            updatePlacement() {
                this.$nextTick(() => {
                    const rect = this.$el.getBoundingClientRect();
                    this.placement = rect.top > 380 ? 'top' : 'bottom';
                });
            },

            // --- HELPERS ---
            getDateStr(day, month, year) {
                let d = new Date(year, month, day);
                return this.formatDate(d);
            },
            formatDate(date) {
                let offset = date.getTimezoneOffset();
                date = new Date(date.getTime() - (offset * 60 * 1000));
                return date.toISOString().split('T')[0];
            },
            formatHuman(dateStr) {
                let date = new Date(dateStr);
                if (isNaN(date.getTime())) return dateStr;
                return date.toLocaleDateString('de-DE', { day: '2-digit', month: '2-digit', year: 'numeric' });
            },

            selectDate(day, month, year) {
                let dateStr = this.getDateStr(day, month, year);
                if (this.mode === 'range') {
                    if (!this.rangeFrom || (this.rangeFrom && this.rangeTo)) {
                        this.rangeFrom = dateStr; this.rangeTo = null;
                    } else if (this.rangeFrom && !this.rangeTo) {
                        if (dateStr < this.rangeFrom) {
                            this.rangeTo = this.rangeFrom; this.rangeFrom = dateStr;
                        } else { this.rangeTo = dateStr; }
                        this.value = { start: this.rangeFrom, end: this.rangeTo };
                        this.close();
                    }
                } else {
                    this.value = dateStr;
                    this.close();
                }
            },

            isSelected(day, month, year) {
                const dStr = this.getDateStr(day, month, year);
                if (this.mode === 'range') return dStr === this.rangeFrom || dStr === this.rangeTo;
                return dStr === this.value;
            },
            isInRange(day, month, year) {
                if (this.mode !== 'range' || !this.rangeFrom || !this.rangeTo) return false;
                const dStr = this.getDateStr(day, month, year);
                return dStr > this.rangeFrom && dStr < this.rangeTo;
            },
            isHoverRange(day, month, year) {
                if (this.mode !== 'range' || !this.rangeFrom || this.rangeTo || !this.hoverDate) return false;
                const dStr = this.getDateStr(day, month, year);
                if (this.hoverDate < this.rangeFrom) return dStr >= this.hoverDate && dStr < this.rangeFrom;
                return dStr > this.rangeFrom && dStr <= this.hoverDate;
            },
            isToday(day, month, year) {
                const today = new Date();
                return today.getDate() === day && today.getMonth() === month && today.getFullYear() === year;
            },
            isDisabled(day, month, year) {
                const dStr = this.getDateStr(day, month, year);
                const dayOfWeek = new Date(year, month, day).getDay();
                if (min && dStr < min) return true;
                if (max && dStr > max) return true;
                return this.disabledDates.some(rule => {
                    if (typeof rule === 'string') return rule === dStr;
                    if (typeof rule === 'number') return rule === dayOfWeek;
                    if (typeof rule === 'object' && rule.from && rule.to) return dStr >= rule.from && dStr <= rule.to;
                    return false;
                });
            },

            prevPage() {
                if (this.view === 'days') {
                    this.cursorMonth--;
                    if (this.cursorMonth < 0) { this.cursorMonth = 11; this.cursorYear--; }
                    this.updateCalendars();
                } else { this.cursorYear -= 12; this.generateYears(); }
            },
            nextPage() {
                if (this.view === 'days') {
                    this.cursorMonth++;
                    if (this.cursorMonth > 11) { this.cursorMonth = 0; this.cursorYear++; }
                    this.updateCalendars();
                } else { this.cursorYear += 12; this.generateYears(); }
            },
            toggleView() { this.view = (this.view === 'days') ? 'years' : 'days'; if(this.view === 'years') this.generateYears(); },
            selectYear(y) { this.cursorYear = y; this.view = 'days'; this.updateCalendars(); },
            generateYears() {
                let startYear = this.cursorYear - 6;
                this.yearsList = Array.from({ length: 12 }, (_, i) => startYear + i);
            },
            gotoToday() {
                let today = new Date();
                this.initDate(today);
                if(this.mode === 'single') {
                    this.value = this.getDateStr(today.getDate(), today.getMonth(), today.getFullYear());
                    this.close();
                }
            },
            get formattedDate() {
                if (this.mode === 'range') {
                    if (!this.rangeFrom) return '';
                    let start = this.formatHuman(this.rangeFrom);
                    if (!this.rangeTo) return start;
                    return start + ' - ' + this.formatHuman(this.rangeTo);
                }
                if (!this.value) return '';
                if (typeof this.value === 'object') return '';
                return this.formatHuman(this.value);
            },
            // Getter für Separate Inputs
            get startDisplay() { return this.rangeFrom ? this.formatHuman(this.rangeFrom) : ''; },
            get endDisplay() { return this.rangeTo ? this.formatHuman(this.rangeTo) : ''; }
        }));

    Alpine.data('bsTimePicker', ({ model, min, max, interval, mode, disable, format }) => ({
        show: false,
        value: model,
        mode: mode,
        min: min,
        max: max,
        interval: interval,
        format: format,
        disabledTimes: disable ?? [],
        rangeFrom: null,
        rangeTo: null,
        hoverTime: null,
        placement: 'bottom',
        timeSlots: [],

        init() {
            this.generateSlots();
            if (this.mode === 'range') {
                if (this.value && typeof this.value === 'object') {
                    this.rangeFrom = this.value.start;
                    this.rangeTo = this.value.end;
                } else if (this.value && typeof this.value === 'string' && this.value.includes(' to ')) {
                    let parts = this.value.split(' to ');
                    this.rangeFrom = parts[0];
                    this.rangeTo = parts[1];
                }
            }
            this.$watch('show', val => {
                if (val) { this.updatePlacement(); this.scrollToSelected(); }
                else { this.hoverTime = null; }
            });
        },

        toggle() { this.show = !this.show; },
        close() { this.show = false; this.hoverTime = null; },

        generateSlots() {
            this.timeSlots = [];
            let current = this.parseTime(this.min);
            let end = this.parseTime(this.max);
            let guard = 0;
            while (current <= end && guard < 1440) {
                let val = this.formatTime24(current);
                let lbl = this.formatDisplay(current);
                this.timeSlots.push({ value: val, label: lbl });
                current.setMinutes(current.getMinutes() + this.interval);
                guard += this.interval;
            }
        },

        selectTime(time) {
            if (this.isDisabled(time)) return;
            if (this.mode === 'range') {
                if (!this.rangeFrom || (this.rangeFrom && this.rangeTo)) {
                    this.rangeFrom = time; this.rangeTo = null;
                } else if (this.rangeFrom && !this.rangeTo) {
                    if (time < this.rangeFrom) { this.rangeTo = this.rangeFrom; this.rangeFrom = time; }
                    else { this.rangeTo = time; }
                    this.value = { start: this.rangeFrom, end: this.rangeTo };
                    this.close();
                }
            } else { this.value = time; this.close(); }
        },

        isSelected(time) {
            if (this.mode === 'range') return time === this.rangeFrom || time === this.rangeTo;
            return time === this.value;
        },
        isInRange(time) {
            if (this.mode !== 'range' || !this.rangeFrom || !this.rangeTo) return false;
            return time > this.rangeFrom && time < this.rangeTo;
        },
        isHoverRange(time) {
            if (this.mode !== 'range' || !this.rangeFrom || this.rangeTo || !this.hoverTime) return false;
            if (this.hoverTime < this.rangeFrom) return time >= this.hoverTime && time < this.rangeFrom;
            return time > this.rangeFrom && time <= this.hoverTime;
        },
        isDisabled(time) {
            return this.disabledTimes.some(rule => {
                if (typeof rule === 'string') return rule === time;
                if (typeof rule === 'object' && rule.from && rule.to) return time >= rule.from && time <= rule.to;
                return false;
            });
        },
        updatePlacement() {
            this.$nextTick(() => {
                const rect = this.$el.getBoundingClientRect();
                this.placement = rect.top > 350 ? 'top' : 'bottom';
            });
        },
        scrollToSelected() {
            this.$nextTick(() => {
                const activeEl = this.$refs.list.querySelector('.active');
                if (activeEl) activeEl.scrollIntoView({ block: 'center' });
            });
        },
        parseTime(timeStr) {
            let [h, m] = timeStr.split(':');
            let d = new Date(); d.setHours(h); d.setMinutes(m); d.setSeconds(0); return d;
        },
        formatDisplay(date) {
            if (this.format === '12') {
                let h = date.getHours(); let m = date.getMinutes().toString().padStart(2, '0');
                let ampm = h >= 12 ? 'PM' : 'AM'; h = h % 12; h = h ? h : 12;
                return `${h}:${m} ${ampm}`;
            }
            return this.formatTime24(date);
        },
        formatTime24(date) {
            let h = date.getHours().toString().padStart(2, '0'); let m = date.getMinutes().toString().padStart(2, '0');
            return `${h}:${m}`;
        },
        formatString(timeStr) {
            if (!timeStr) return '';
            let d = this.parseTime(timeStr);
            return this.formatDisplay(d);
        },
        get formattedValue() {
            if (this.mode === 'range') {
                if (!this.rangeFrom) return '';
                let start = this.formatString(this.rangeFrom);
                if (!this.rangeTo) return start;
                return `${start} - ${this.formatString(this.rangeTo)}`;
            }
            return this.formatString(this.value);
        },
        get startDisplay() { return this.formatString(this.rangeFrom); },
        get endDisplay() { return this.formatString(this.rangeTo); }
    }));

    Alpine.data('bsFileUpload', ({ name, id, multiple }) => ({
        isDropping: false,
        isUploading: false,
        progress: 0,
        // NEU: Zähler für die Anzeige
        currentIndex: 0,
        totalFiles: 0,

        name: name,
        inputId: id,
        multiple: multiple,

        init() {
            // Keine Event-Listener mehr nötig für sequenziellen Upload
        },

        trigger() {
            const input = document.getElementById(this.inputId);
            if (input) input.click();
        },

        onDragOver(e) {
            e.preventDefault();
            this.isDropping = true;
        },

        onDragLeave(e) {
            e.preventDefault();
            this.isDropping = false;
        },

        onDrop(e) {
            e.preventDefault();
            this.isDropping = false;
            let files = e.dataTransfer.files;
            if (files.length > 0) this.uploadFiles(files);
        },

        handleFileSelect(e) {
            if (e.target.files.length > 0) {
                this.uploadFiles(e.target.files);
            }
        },

        async uploadFiles(fileList) {
            this.isUploading = true;
            this.progress = 0;

            const files = Array.from(fileList);
            const input = document.getElementById(this.inputId);

            // NEU: Gesamtanzahl setzen
            this.totalFiles = files.length;
            this.currentIndex = 0;

            let currentCount = 0;
            if (this.multiple) {
                let currentData = await this.$wire.get(this.name);
                currentCount = Array.isArray(currentData) ? currentData.length : 0;
            }

            for (let i = 0; i < files.length; i++) {
                // NEU: Aktuellen Schritt setzen (1-basiert)
                this.currentIndex = i + 1;

                const file = files[i];
                const targetName = this.multiple
                    ? this.name + '.' + (currentCount + i)
                    : this.name;

                await new Promise((resolve) => {
                    this.$wire.upload(
                        targetName,
                        file,
                        () => resolve(),
                        () => resolve(), // Bei Fehler weitermachen
                        (event) => {
                            this.progress = event.detail.progress;
                        }
                    );
                });

                this.progress = 0;
            }

            this.isUploading = false;
            this.progress = 0;
            // Reset Zähler
            this.currentIndex = 0;
            this.totalFiles = 0;

            if(input) input.value = '';
        }
    }));

    Alpine.data('bsTimeline', (config) => ({
        expanded: false,
        cutoff: config.cutoff,
        totalItems: 0,

        init() {
            this.countItems();

            // MutationObserver: Falls Livewire Elemente nachlädt, zählen wir neu.
            // Das ist nur für die Zahl im Button ("3 weitere anzeigen") wichtig.
            // Das Verstecken macht das CSS automatisch.
            let observer = new MutationObserver(() => {
                this.countItems();
                // Optional: Beim Blättern wieder zuklappen
                this.expanded = false;
            });

            if (this.$refs.listContainer) {
                observer.observe(this.$refs.listContainer, { childList: true });
            }
        },

        countItems() {
            if(!this.$refs.listContainer) return;
            // Zählt nur direkte DIV Kinder
            this.totalItems = Array.from(this.$refs.listContainer.children)
                .filter(el => el.tagName === 'DIV').length;
        },

        get remainingCount() {
            return Math.max(0, this.totalItems - this.cutoff);
        },

        get shouldShowExpand() {
            return !this.expanded && this.totalItems > this.cutoff;
        },

        get shouldShowCollapse() {
            return this.expanded && this.totalItems > this.cutoff;
        }
    }));

    Alpine.data('bsCopyBtn', (config) => ({
        copied: false,
        textVal: config.text,
        targetId: config.target,
        duration: config.duration,

        copyToClipboard() {
            let content = this.textVal;
            if (this.targetId) {
                const el = document.getElementById(this.targetId);
                if (el) content = el.value || el.innerText;
            }
            if (!content) return;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(content).then(() => this.triggerSuccess())
                    .catch(e => console.error(e));
            } else {
                // Fallback
                const ta = document.createElement("textarea");
                ta.value = content;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand("copy");
                ta.remove();
                this.triggerSuccess();
            }
        },

        triggerSuccess() {
            this.copied = true;
            setTimeout(() => this.copied = false, this.duration);
        }
    }));

    Alpine.data('bsEditor', (model) => ({
        content: model,
        config: {},
        editor: null,
        isFocused: false,

        init() {
            // 1. Config lesen
            if (this.$el.dataset.config) {
                this.config = JSON.parse(this.$el.dataset.config);
            }

            const finalModules = this.config.modules || {};

            // 2. Quill Starten
            this.editor = new Quill(this.$refs.quillEditor, {
                theme: this.config.theme || 'snow',
                placeholder: this.config.placeholder,
                modules: finalModules
            });

            if (this.config.height) this.$refs.quillEditor.style.height = this.config.height;
            if (this.content) this.editor.root.innerHTML = this.content;

            // --- FLÜSSIGKEITS-LOGIK ---

            // A) Fokus Tracking: Verhindert, dass Livewire Updates überschreibt, während du tippst
            this.editor.on('selection-change', (range) => {
                this.isFocused = !!range;
            });

            // B) Update Funktion mit Debounce Unterstützung
            const updateContent = () => {
                let html = this.editor.root.innerHTML;
                if (html === '<p><br></p>') html = null;
                this.content = html; // Schreibt in Entangle -> Sendet an Server
            };

            // C) Debounce anwenden (falls in Blade konfiguriert)
            // Wenn du wire:model.live.debounce.500ms nutzt, ist this.config.debounce = 500
            let changeHandler = updateContent;

            if (this.config.debounce > 0) {
                changeHandler = this.debounce(updateContent, this.config.debounce);
            }

            // Event Listener
            this.editor.on('text-change', changeHandler);

            // --- EXTERNE UPDATES ---

            this.$watch('content', (newContent) => {
                // WICHTIG: Wenn wir den Fokus haben, ignorieren wir eingehende Daten.
                // Das verhindert das "Echo-Ruckeln".
                if (this.isFocused) return;

                if (this.editor.root.innerHTML !== newContent) {
                    this.editor.root.innerHTML = newContent ?? '';
                }
            });
        },

        // Helper Funktion für Verzögerung
        debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
    }));

    Alpine.data('bsContextMenu', (targetId) => ({
        isOpen: false,
        isVisible: false,
        position: { x: 0, y: 0 },

        // NEU: Style als Computed Property
        // Das löst den ReferenceError, da wir hier sicher auf 'this' zugreifen können
        get style() {
            return `
                position: fixed; 
                top: ${this.position.y}px; 
                left: ${this.position.x}px; 
                z-index: 9999;
                visibility: ${this.isVisible ? 'visible' : 'hidden'};
            `;
        },

        init() {
            this.$nextTick(() => {
                const element = document.getElementById(targetId);
                if (element) {
                    element.addEventListener('contextmenu', (e) => {
                        e.preventDefault();
                        this.open(e);
                    });
                    window.addEventListener('contextmenu', (e) => {
                        if (!element.contains(e.target)) this.close();
                    });
                }
            });

            window.addEventListener('bs-context-menu-opened', (event) => {
                if (event.detail !== targetId) this.close();
            });
        },

        open(event) {
            window.dispatchEvent(new CustomEvent('bs-context-menu-opened', { detail: targetId }));

            this.position.x = event.clientX;
            this.position.y = event.clientY;

            // Unsichtbar starten
            this.isVisible = false;
            this.isOpen = true;

            this.$nextTick(() => {
                // Im nächsten Frame: Messen & Korrigieren
                const el = this.$refs.menuPanel;
                if (!el) return;

                const height = el.offsetHeight;
                const width = el.offsetWidth;
                const winHeight = window.innerHeight;
                const winWidth = window.innerWidth;

                // Y-Achse (Unten)
                if ((this.position.y + height) > winHeight) {
                    this.position.y -= height;
                }

                // X-Achse (Rechts)
                if ((this.position.x + width) > winWidth) {
                    this.position.x -= width;
                }

                // Sichtbar schalten
                this.isVisible = true;
            });
        },

        close() {
            this.isOpen = false;
            this.isVisible = false;
        }
    }));

    Alpine.data('bsContextMenuItem', (config) => ({
        // Config enthält: confirm, confirmText, action, dispatch, params
        config: config,

        execute() {
            // 1. Menü schließen
            // Wir gehen davon aus, dass im Eltern-Element (dem Dropdown) eine close() Funktion existiert.
            // Alpine sucht automatisch im Scope nach oben.
            if (typeof this.close === 'function') {
                this.close();
            }

            // 2. Bestätigung (Confirm)
            if (this.config.confirm) {
                if (!confirm(this.config.confirmText)) {
                    return; // Abbrechen
                }
            }

            // 3. Livewire Action ausführen
            // In Livewire 3 ist 'this.$wire' automatisch verfügbar
            if (!this.$wire) {
                console.error('bsContextMenuItem: No Livewire context found!');
                return;
            }

            if (this.config.dispatch) {
                // Fall A: Event dispatchen
                this.$wire.dispatch(this.config.dispatch, this.config.params);

            } else if (this.config.action) {
                // Fall B: Methode aufrufen
                const args = Array.isArray(this.config.params) ? this.config.params : [this.config.params];
                this.$wire[this.config.action](args);
            }
        }
    }));

    Alpine.data('bsShortcut', (config) => ({
        key: config.key,
        prevent: config.prevent ?? true,
        stop: config.stop ?? false,
        handler: config.handler,

        init() {
            // Warten bis DOM bereit
            this.$nextTick(() => {
                if (this.key) {
                    // MODUS A: Expliziter Key -> String parsen
                    this.registerKeyString(this.key);
                } else {
                    // MODUS B: Magic Slot -> DOM scannen
                    this.registerFromSlot();
                }
            });
        },

        registerKeyString(keyString) {
            const parts = keyString.toLowerCase().replace(/\s/g, '').split('+');
            this.attachListener(parts);
        },

        registerFromSlot() {
            const elements = Array.from(this.$el.querySelectorAll('[data-shortcut-key]'));
            if (elements.length === 0) return;

            const keys = elements.map(el => el.getAttribute('data-shortcut-key'));
            this.attachListener(keys);
        },

        attachListener(keys) {
            const modifiers = ['ctrl', 'alt', 'shift', 'meta'];

            // Helper: Mapping (esc -> escape)
            const mapKey = (k) => ({'cmd':'meta','win':'meta','opt':'alt','esc':'escape','del':'delete'}[k] || k);

            // Aufteilen
            const requiredMods = keys.filter(k => modifiers.includes(k));
            let mainKey = keys.find(k => !modifiers.includes(k));
            if (mainKey) mainKey = mapKey(mainKey);

            window.addEventListener('keydown', (e) => {
                // Modifiers prüfen
                const check = (mod, eventProp) => requiredMods.some(m => m === mod) === eventProp;

                const ctrl   = check('ctrl', e.ctrlKey);
                const alt    = check('alt', e.altKey) || check('opt', e.altKey); // opt alias
                const shift  = check('shift', e.shiftKey);
                const meta   = check('meta', e.metaKey) || check('cmd', e.metaKey);

                // Key prüfen
                const keyHit = mainKey ? (e.key.toLowerCase() === mainKey) : true;

                if (ctrl && alt && shift && meta && keyHit) {
                    if (this.prevent) e.preventDefault();
                    if (this.stop) e.stopPropagation();

                    // Action ausführen
                    this.handler();
                }
            });
        }
    }));

    Alpine.data('bsColorPicker', (initialModel) => ({
        // textValue ist unser Haupt-Model (entweder Livewire Entangle oder String)
        textValue: initialModel,

        // pickerValue ist NUR für das <input type="color"> (muss immer #RRGGBB sein)
        pickerValue: '#000000',

        init() {
            // Fallback, falls leer
            if (!this.textValue) this.textValue = '#000000';

            // Initialen Status setzen
            this.syncPickerFromText();

            // Beobachten, falls sich der Wert von außen ändert (z.B. durch Server)
            this.$watch('textValue', () => this.syncPickerFromText());
        },

        // Trigger: User wählt Farbe im bunten Picker
        updateFromPicker(hex) {
            this.pickerValue = hex;
            this.textValue = hex;
        },

        // Trigger: User tippt Text (oder Preset) -> Wir versuchen es zu verstehen
        syncPickerFromText() {
            if (!this.textValue) return;

            // Smart Convert: 'red' -> '#ff0000'
            const converted = this.nameToHex(this.textValue);

            // Nur updaten, wenn es eine valide Farbe ergab
            if (converted && converted !== '#000000') {
                this.pickerValue = converted;
            } else if (this.isBlack(this.textValue)) {
                // Sonderfall Schwarz (da nameToHex bei Fehlern oft schwarz/transparent liefert)
                this.pickerValue = '#000000';
            }
        },

        // Hilfsfunktion: Wandelt JEDEN CSS-Farbnamen in Hex um
        nameToHex(color) {
            const ctx = document.createElement('canvas').getContext('2d');
            ctx.fillStyle = color;
            return ctx.fillStyle; // Browser liefert hier meist Hex zurück
        },

        // Hilfsfunktion: Prüft explizit auf "Schwarz", da Canvas bei Fehlern auch #000000 sein kann
        isBlack(val) {
            const v = val.toLowerCase().trim();
            return v === 'black' || v === '#000' || v === '#000000' || v === 'rgb(0,0,0)';
        }
    }));

    Alpine.data('bsAutocomplete', ({ searchModel, selectModel, selectField, isStatic, minChars }) => ({
        isOpen: false,
        highlightIndex: -1,
        searchModel: searchModel,
        selectModel: selectModel,
        selectField: selectField,
        isStatic: isStatic,
        searchTerm: '',
        filteredCount: 0,
        staticItems: [],
        minChars: minChars || 2,
        observer: null,
        pendingOpen: false,

        init() {
            if (this.isStatic) {
                this.$nextTick(() => {
                    this.loadStaticItems();
                });
            } else {
                this.$nextTick(() => {
                    this.setupLivewireObserver();
                });
            }
        },

        setupLivewireObserver() {
            const dropdown = this.$refs.livewireDropdown;
            if (!dropdown) {
                console.log('livewireDropdown ref not found, retrying...');
                setTimeout(() => this.setupLivewireObserver(), 100);
                return;
            }

            this.observer = new MutationObserver((_mutations) => {
                this.$nextTick(() => {
                    this.checkLivewireResults();
                });
            });

            this.observer.observe(dropdown, {
                childList: true,
                subtree: true,
            });

            this.checkLivewireResults();
        },

        checkLivewireResults() {
            const items = this.$refs.livewireResults?.querySelectorAll('li') || [];
            const count = items.length;

            this.filteredCount = count;

            if (count > 0 && this.meetsMinChars()) {
                if (document.activeElement === this.$refs.input || this.pendingOpen) {
                    if (!this.isExactMatch()) {
                        this.isOpen = true;
                        this.highlightIndex = 0;
                    }
                    this.pendingOpen = false;
                }
            } else if (count === 0 && !this.isOpen) {
                if (this.meetsMinChars() && document.activeElement === this.$refs.input) {
                    this.pendingOpen = true;
                }
            }
        },

        loadStaticItems() {
            if (!this.$refs.staticItems) return;

            const items = this.$refs.staticItems.querySelectorAll('[data-autocomplete-item]');

            this.staticItems = Array.from(items).map((el, index) => ({
                element: el,
                value: el.dataset.value,
                label: el.dataset.label,
                index: index,
                visible: true,
                visibleIndex: index
            }));

            this.filteredCount = this.staticItems.length;
        },

        meetsMinChars() {
            if (this.isStatic) {
                return this.searchTerm.length >= this.minChars;
            } else {
                const value = this.$refs.input?.value || '';
                return value.length >= this.minChars;
            }
        },

        isExactMatch() {
            const inputValue = this.isStatic ? this.searchTerm.trim() : this.$refs.input?.value.trim() || '';

            if (!inputValue) return false;

            if (this.isStatic) {
                return this.staticItems.some(item =>
                    item.label.toLowerCase() === inputValue.toLowerCase()
                );
            } else {
                const items = this.$refs.livewireResults?.querySelectorAll('[data-display]') || [];
                return Array.from(items).some(item =>
                    item.dataset.display.toLowerCase() === inputValue.toLowerCase()
                );
            }
        },

        onFocus() {
            if (this.isExactMatch()) {
                return;
            }

            if (!this.isStatic) {
                this.pendingOpen = true;
                this.checkLivewireResults();
            } else {
                if (this.meetsMinChars()) {
                    this.openDropdown();
                }
            }
        },

        onInputChange() {
            if (this.isStatic) {
                if (this.meetsMinChars()) {
                    this.filterStaticItems();
                } else {
                    this.closeDropdown();
                }
            } else {
                if (this.meetsMinChars()) {
                    this.pendingOpen = true;
                } else {
                    this.closeDropdown();
                    this.pendingOpen = false;
                }
            }
        },

        filterStaticItems() {
            const term = this.searchTerm.toLowerCase().trim();
            let visibleCount = 0;

            this.staticItems.forEach((item) => {
                const matches = item.label.toLowerCase().includes(term);
                item.visible = matches;
                item.element.style.display = matches ? '' : 'none';

                if (matches) {
                    item.visibleIndex = visibleCount;
                    visibleCount++;
                }
            });

            this.filteredCount = visibleCount;
            this.highlightIndex = visibleCount > 0 ? 0 : -1;

            if (visibleCount > 0 && this.meetsMinChars() && !this.isExactMatch()) {
                this.isOpen = true;
            } else if (this.isExactMatch()) {
                this.isOpen = false;
            }
        },

        openDropdown() {
            if (!this.meetsMinChars() || this.isExactMatch()) {
                return;
            }

            if (this.isStatic && this.staticItems.length === 0) {
                this.loadStaticItems();
            }

            if (this.isStatic) {
                this.filterStaticItems();
            }

            this.isOpen = true;
            this.highlightIndex = 0;
        },

        closeDropdown() {
            this.isOpen = false;
            this.highlightIndex = -1;
            this.pendingOpen = false;
        },

        onArrowDown() {
            if (!this.isOpen) {
                if (this.meetsMinChars() && !this.isExactMatch()) {
                    this.openDropdown();
                }
                return;
            }

            if (this.highlightIndex < this.filteredCount - 1) {
                this.highlightIndex++;
                this.scrollToHighlighted();
            } else {
                this.highlightIndex = 0;
                this.scrollToHighlighted();
            }
        },

        onArrowUp() {
            if (!this.isOpen) return;

            if (this.highlightIndex > 0) {
                this.highlightIndex--;
                this.scrollToHighlighted();
            } else {
                this.highlightIndex = this.filteredCount - 1;
                this.scrollToHighlighted();
            }
        },

        scrollToHighlighted() {
            this.$nextTick(() => {
                let highlightedEl;

                if (this.isStatic) {
                    const visibleItems = this.staticItems.filter(item => item.visible);
                    const item = visibleItems[this.highlightIndex];
                    if (item) {
                        highlightedEl = item.element.querySelector('a');
                    }
                } else {
                    // Suche im livewireDropdown, nicht in $el
                    highlightedEl = this.$refs.livewireDropdown?.querySelector(`[data-index='${this.highlightIndex}']`);
                }

                if (highlightedEl) {
                    highlightedEl.scrollIntoView({
                        block: 'nearest',
                        behavior: 'smooth'
                    });
                }
            });
        },

        onEnter() {
            if (!this.isOpen || this.highlightIndex < 0) {
                return;
            }

            if (this.isStatic) {
                const visibleItems = this.staticItems.filter(item => item.visible);
                const item = visibleItems[this.highlightIndex];

                if (item) {
                    this.selectStaticItem(item);
                } else {
                    console.error('Item not found at index', this.highlightIndex);
                }
            } else {

                // Suche im livewireDropdown oder livewireResults, nicht in $el
                let el = this.$refs.livewireDropdown?.querySelector(`[data-index='${this.highlightIndex}']`);

                if (!el) {
                    el = this.$refs.livewireResults?.querySelector(`li:nth-child(${this.highlightIndex + 1}) a[data-index]`);
                }


                if (el) {
                    this.selectItem(el);
                } else {
                    console.error('Element not found for index', this.highlightIndex);
                    console.log('livewireDropdown:', this.$refs.livewireDropdown);
                    console.log('All [data-index] elements:', this.$refs.livewireDropdown?.querySelectorAll('[data-index]'));
                }
            }
        },

        isHighlighted(linkEl) {
            if (!this.isStatic) return false;

            const li = linkEl.closest('[data-autocomplete-item]');
            const item = this.staticItems.find(i => i.element === li);

            return item && item.visible && item.visibleIndex === this.highlightIndex;
        },

        highlightByElement(linkEl) {
            if (!this.isStatic) return;

            const li = linkEl.closest('[data-autocomplete-item]');
            const item = this.staticItems.find(i => i.element === li);

            if (item && item.visible) {
                this.highlightIndex = item.visibleIndex;
            }
        },

        selectFromStatic(linkEl) {
            const li = linkEl.closest('[data-autocomplete-item]');
            const item = this.staticItems.find(i => i.element === li);
            if (item) {
                this.selectStaticItem(item);
            }
        },

        selectStaticItem(item) {
            this.searchTerm = item.label;

            if (this.$wire && this.selectModel) {
                this.$wire.set(this.selectModel, item.value);
            }

            this.closeDropdown();

            this.$dispatch('autocomplete-selected', {
                value: item.value,
                display: item.label
            });
        },

        selectItem(el) {
            const selectValue = el.dataset.select;
            const displayValue = el.dataset.display;

            if (this.$wire) {
                if (this.selectModel) {
                    this.$wire.set(this.selectModel, selectValue);
                }
                if (this.searchModel) {
                    this.$wire.set(this.searchModel, displayValue);
                }
            }

            this.closeDropdown();

            this.$dispatch('autocomplete-selected', {
                value: selectValue,
                display: displayValue
            });
        },

        destroy() {
            if (this.observer) {
                this.observer.disconnect();
            }
        }
    }));
}


function registerAlpineDirective() {
    Alpine.directive('bs-tooltip', (el, { expression }, { evaluate, cleanup }) => {

        // Den Text/Titel aus dem Alpine-Ausdruck holen
        let title = evaluate(expression);
        // Falls kein Text da ist, abbrechen
        if (!title) return;

        // Bootstrap Tooltip instanziieren
        let tooltip = new bootstrap.Tooltip(el, {
            title: title,

            // Placement aus data-Attribut holen (Fallback: 'top')
            placement: el.dataset.bsPlacement || 'top',

            // HIER IST DIE ÄNDERUNG: Custom Class explizit übernehmen
            // Wir lesen das data-Attribut, das unsere Blade-Component setzt
            customClass: el.dataset.bsCustomClass || '',

            trigger: 'hover focus',
            html: false
        });

        // WICHTIG: Cleanup für Livewire
        // Wenn Livewire das Element aus dem DOM wirft, zerstören wir den Tooltip
        cleanup(() => {
            tooltip.dispose();
        });
    });

    Alpine.directive('bs-popover', (el, { expression }, { evaluate, cleanup }) => {

        // Optional: Falls man <span x-popover="'Mein Inhalt'"> nutzt,
        // nehmen wir das als Content.
        let content = expression ? evaluate(expression) : null;

        // Bootstrap Konfiguration
        const popover = new bootstrap.Popover(el, {
            // Wenn Content im x-popover übergeben wurde, nutzen wir ihn,
            // sonst sucht Bootstrap automatisch nach data-bs-content
            content: content || el.dataset.bsContent,

            title: el.dataset.bsTitle || '', // Überschrift

            placement: el.dataset.bsPlacement || 'right',
            customClass: el.dataset.bsCustomClass || '',

            // WICHTIG: 'focus' macht das Popover "dismissible".
            // Klickt der User woanders hin, geht es zu.
            trigger: el.dataset.bsTrigger || 'focus',

            // 'body' verhindert oft Z-Index/Overflow Probleme in Tabellen oder Cards
            container: 'body',
            html: false // XSS Schutz
        });

        // Livewire Cleanup
        cleanup(() => {
            popover.dispose();
        });
    });
}
