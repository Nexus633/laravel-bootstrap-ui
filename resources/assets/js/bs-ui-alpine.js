if (!window.__bs_alpine_registered) {
    window.__bs_alpine_registered = true;

    document.addEventListener('alpine:init', () => {
        registerAlpineFunctions();
        registerAlpineDirective();
    });
}
function registerAlpineFunctions() {
    // ==========================================
    // UI STATE (Tabs / Accordion)
    // ==========================================
    Alpine.data('bsUiState', (id, type = 'tab', usePersist = false) => ({
        activeItems: [],
        storageKey: 'bs-ui-' + id,
        _lwHook: null, // Speicher für Livewire Hook

        init() {
            this.loadState();
            this.$nextTick(() => this.restoreState());
            this.registerListeners();

            if (typeof Livewire !== 'undefined') {
                // Hook speichern, um ihn später entfernen zu können (falls nötig)
                // Hinweis: Livewire Hooks in v3 sind oft global, aber cleanup schadet nicht
                this._lwHook = Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => this.$nextTick(() => this.restoreState()));
                });
            }
        },

        destroy() {
            // Livewire Hook deregistrieren
            if (this._lwHook && typeof Livewire !== 'undefined' && typeof this._lwHook === 'function') {
                this._lwHook(); // In LW3 gibt der Hook eine Cleanup-Funktion zurück
            }
        },

        registerListeners() {
            // DOM-Listener auf this.$el werden vom Browser automatisch aufgeräumt,
            // wenn das Element aus dem DOM entfernt wird. Hier ist kein manuelles
            // removeEventListener nötig, solange es nicht 'window' oder 'document' ist.
            if (type === 'tab') {
                this.$el.addEventListener('shown.bs.tab', (e) => {
                    const target = e.target.getAttribute('data-bs-target');
                    this.saveState([target]);
                });
            } else if (type === 'accordion') {
                this.$el.addEventListener('shown.bs.collapse', (e) => {
                    if (e.target.closest('.accordion') === this.$el) this.addItem(e.target.id);
                });
                this.$el.addEventListener('hidden.bs.collapse', (e) => {
                    if (e.target.closest('.accordion') === this.$el) this.removeItem(e.target.id);
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
            if (!this.activeItems?.length || !document.body.contains(this.$el)) return;

            if (type === 'tab') {
                const activeId = this.activeItems[0];
                const trigger = this.$el.querySelector(`[data-bs-target="${activeId}"]`);
                if (trigger && !trigger.classList.contains('active')) {
                    bootstrap.Tab.getOrCreateInstance(trigger).show();
                }
            } else if (type === 'accordion') {
                this.activeItems.forEach(itemId => {
                    const content = document.getElementById(itemId);
                    if (content && !content.classList.contains('show')) {
                        bootstrap.Collapse.getOrCreateInstance(content, { toggle: false }).show();
                    }
                });
            }
        },
        saveState(items) {
            this.activeItems = items;
            const storage = usePersist ? localStorage : sessionStorage;
            items.length > 0 ? storage.setItem(this.storageKey, JSON.stringify(items)) : storage.removeItem(this.storageKey);
        },
        loadState() {
            const storage = usePersist ? localStorage : sessionStorage;
            const stored = storage.getItem(this.storageKey);
            if (stored) {
                try {
                    this.activeItems = JSON.parse(stored);
                    if (!Array.isArray(this.activeItems)) this.activeItems = [stored];
                } catch { this.activeItems = []; }
            } else { this.activeItems = []; }
        }
    }));

    Alpine.data('bsTableSticky', () => ({
        _resizeHandler: null,
        _lwHook: null,

        init() {
            this.updateSticky();

            // Handler referenzieren für Cleanup
            this._resizeHandler = () => this.updateSticky();
            window.addEventListener('resize', this._resizeHandler);

            if (typeof Livewire !== 'undefined') {
                this._lwHook = Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => this.$nextTick(() => this.updateSticky()));
                });
            }
        },

        destroy() {
            // WICHTIG: Resize Listener entfernen
            if (this._resizeHandler) {
                window.removeEventListener('resize', this._resizeHandler);
                this._resizeHandler = null;
            }

            if (this._lwHook && typeof this._lwHook === 'function') {
                this._lwHook();
            }
        },

        updateSticky() {
            const table = this.$el;
            const rows = table.querySelectorAll('tr');
            if (rows.length === 0) return;

            const stickyIndices = new Set();
            const headerCells = table.querySelectorAll('thead tr:first-child th');
            let colIdx = 0;

            headerCells.forEach((cell) => {
                const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);
                if (colspan === 1 && cell.hasAttribute('data-sticky')) {
                    stickyIndices.add(colIdx);
                }
                colIdx += colspan;
            });

            rows.forEach((row) => {
                const cells = Array.from(row.children);
                const parentTag = row.parentElement.tagName;
                const columnWidths = new Map();
                let realColumnIndex = 0;

                cells.forEach((cell) => {
                    const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);
                    const cellWidth = cell.getBoundingClientRect().width;
                    const widthPerColumn = cellWidth / colspan;
                    for (let i = 0; i < colspan; i++) columnWidths.set(realColumnIndex + i, widthPerColumn);
                    realColumnIndex += colspan;
                });

                realColumnIndex = 0;
                cells.forEach((cell) => {
                    const colspan = parseInt(cell.getAttribute('colspan') || '1', 10);
                    const isExplicitSticky = cell.hasAttribute('data-sticky');

                    if (colspan > 1) {
                        this._resetCell(cell);
                        realColumnIndex += colspan;
                        return;
                    }

                    const shouldBeSticky = isExplicitSticky || stickyIndices.has(realColumnIndex);

                    if (shouldBeSticky) {
                        let leftOffset = 0;
                        for (let col = 0; col < realColumnIndex; col++) {
                            if (stickyIndices.has(col)) leftOffset += (columnWidths.get(col) || 0);
                        }
                        cell.style.left = leftOffset + 'px';
                        cell.style.position = 'sticky';
                        cell.classList.add('table-sticky-cell');
                        cell.style.zIndex = parentTag === 'THEAD' ? '5' : (parentTag === 'TFOOT' ? '3' : '2');
                    } else {
                        this._resetCell(cell);
                    }
                    realColumnIndex += colspan;
                });
            });
        },
        _resetCell(cell) {
            cell.style.left = '';
            cell.style.position = '';
            cell.style.zIndex = '';
            cell.classList.remove('table-sticky-cell');
        }
    }));

    // ==========================================
    // DATE PICKER (Rein Logisch, kein Leak-Risk)
    // ==========================================
    Alpine.data('bsDatePicker', ({ model, min, max, mode, disable, months, days, double }) => ({
        show: false, value: model, mode: mode, double: double, disabledDates: disable ?? [], rangeFrom: null, rangeTo: null,
        hoverDate: null, view: 'days', placement: 'bottom', cursorMonth: '', cursorYear: '', calendars: [], yearsList: [],
        monthNames: months, dayNames: days,

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
                this.calendars.push({ month: m, year: y, monthName: this.monthNames[m], ...this.calculateDays(m, y) });
            }
        },
        calculateDays(month, year) {
            let firstDayOfMonth = new Date(year, month, 1).getDay();
            let jsDayToMonStart = (day) => (day === 0 ? 6 : day - 1);
            let firstDayIndex = jsDayToMonStart(firstDayOfMonth);
            let daysCount = new Date(year, month + 1, 0).getDate();
            return { blankDays: new Array(firstDayIndex), days: new Array(daysCount).fill().map((_, i) => i + 1) };
        },
        toggle() {
            this.show = !this.show;
            if (this.show) {
                this.updatePlacement();
                this.view = 'days';
                if (this.mode === 'single' && !this.value) this.initDate(new Date());
                if (this.mode === 'range' && !this.rangeFrom) this.initDate(new Date());
            } else { this.hoverDate = null; }
        },
        close() { this.show = false; this.hoverDate = null; },
        updatePlacement() {
            this.$nextTick(() => {
                const rect = this.$el.getBoundingClientRect();
                this.placement = rect.top > 380 ? 'top' : 'bottom';
            });
        },
        getDateStr(day, month, year) { return this.formatDate(new Date(year, month, day)); },
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
                    if (dateStr < this.rangeFrom) { this.rangeTo = this.rangeFrom; this.rangeFrom = dateStr; }
                    else { this.rangeTo = dateStr; }
                    this.value = { start: this.rangeFrom, end: this.rangeTo };
                    this.close();
                }
            } else { this.value = dateStr; this.close(); }
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
                this.cursorMonth--; if (this.cursorMonth < 0) { this.cursorMonth = 11; this.cursorYear--; } this.updateCalendars();
            } else { this.cursorYear -= 12; this.generateYears(); }
        },
        nextPage() {
            if (this.view === 'days') {
                this.cursorMonth++; if (this.cursorMonth > 11) { this.cursorMonth = 0; this.cursorYear++; } this.updateCalendars();
            } else { this.cursorYear += 12; this.generateYears(); }
        },
        toggleView() { this.view = (this.view === 'days') ? 'years' : 'days'; if (this.view === 'years') this.generateYears(); },
        selectYear(y) { this.cursorYear = y; this.view = 'days'; this.updateCalendars(); },
        generateYears() { let startYear = this.cursorYear - 6; this.yearsList = Array.from({ length: 12 }, (_, i) => startYear + i); },
        gotoToday() {
            let today = new Date(); this.initDate(today);
            if (this.mode === 'single') { this.value = this.getDateStr(today.getDate(), today.getMonth(), today.getFullYear()); this.close(); }
        },
        get formattedDate() {
            if (this.mode === 'range') {
                if (!this.rangeFrom) return '';
                let start = this.formatHuman(this.rangeFrom);
                if (!this.rangeTo) return start;
                return start + ' - ' + this.formatHuman(this.rangeTo);
            }
            if (!this.value || typeof this.value === 'object') return '';
            return this.formatHuman(this.value);
        },
        get startDisplay() { return this.rangeFrom ? this.formatHuman(this.rangeFrom) : ''; },
        get endDisplay() { return this.rangeTo ? this.formatHuman(this.rangeTo) : ''; }
    }));

    // ==========================================
    // TIME PICKER
    // ==========================================
    Alpine.data('bsTimePicker', ({ model, min, max, interval, mode, disable, format }) => ({
        show: false, value: model, mode: mode, min: min, max: max, interval: interval, format: format,
        disabledTimes: disable ?? [], rangeFrom: null, rangeTo: null, hoverTime: null, placement: 'bottom', timeSlots: [],
        init() {
            this.generateSlots();
            if (this.mode === 'range') {
                if (this.value && typeof this.value === 'object') {
                    this.rangeFrom = this.value.start; this.rangeTo = this.value.end;
                } else if (this.value && typeof this.value === 'string' && this.value.includes(' to ')) {
                    let parts = this.value.split(' to '); this.rangeFrom = parts[0]; this.rangeTo = parts[1];
                }
            }
            this.$watch('show', val => {
                if (val) { this.updatePlacement(); this.scrollToSelected(); } else { this.hoverTime = null; }
            });
        },
        toggle() { this.show = !this.show; },
        close() { this.show = false; this.hoverTime = null; },
        generateSlots() {
            this.timeSlots = [];
            let current = this.parseTime(this.min); let end = this.parseTime(this.max); let guard = 0;
            while (current <= end && guard < 1440) {
                let val = this.formatTime24(current); let lbl = this.formatDisplay(current);
                this.timeSlots.push({ value: val, label: lbl });
                current.setMinutes(current.getMinutes() + this.interval); guard += this.interval;
            }
        },
        selectTime(time) {
            if (this.isDisabled(time)) return;
            if (this.mode === 'range') {
                if (!this.rangeFrom || (this.rangeFrom && this.rangeTo)) {
                    this.rangeFrom = time; this.rangeTo = null;
                } else if (this.rangeFrom && !this.rangeTo) {
                    if (time < this.rangeFrom) { this.rangeTo = this.rangeFrom; this.rangeFrom = time; } else { this.rangeTo = time; }
                    this.value = { start: this.rangeFrom, end: this.rangeTo }; this.close();
                }
            } else { this.value = time; this.close(); }
        },
        isSelected(time) { if (this.mode === 'range') return time === this.rangeFrom || time === this.rangeTo; return time === this.value; },
        isInRange(time) { if (this.mode !== 'range' || !this.rangeFrom || !this.rangeTo) return false; return time > this.rangeFrom && time < this.rangeTo; },
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
        updatePlacement() { this.$nextTick(() => { const rect = this.$el.getBoundingClientRect(); this.placement = rect.top > 350 ? 'top' : 'bottom'; }); },
        scrollToSelected() { this.$nextTick(() => { const activeEl = this.$refs.list.querySelector('.active'); if (activeEl) activeEl.scrollIntoView({ block: 'center' }); }); },
        parseTime(timeStr) { let [h, m] = timeStr.split(':'); let d = new Date(); d.setHours(h); d.setMinutes(m); d.setSeconds(0); return d; },
        formatDisplay(date) {
            if (this.format === '12') {
                let h = date.getHours(); let m = date.getMinutes().toString().padStart(2, '0');
                let ampm = h >= 12 ? 'PM' : 'AM'; h = h % 12; h = h ? h : 12;
                return `${h}:${m} ${ampm}`;
            } return this.formatTime24(date);
        },
        formatTime24(date) { let h = date.getHours().toString().padStart(2, '0'); let m = date.getMinutes().toString().padStart(2, '0'); return `${h}:${m}`; },
        formatString(timeStr) { if (!timeStr) return ''; let d = this.parseTime(timeStr); return this.formatDisplay(d); },
        get formattedValue() {
            if (this.mode === 'range') {
                if (!this.rangeFrom) return ''; let start = this.formatString(this.rangeFrom);
                if (!this.rangeTo) return start; return `${start} - ${this.formatString(this.rangeTo)}`;
            } return this.formatString(this.value);
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

            this._observer = new MutationObserver(() => {
                this.countItems();
                this.expanded = false;
            });

            if (this.$refs.listContainer) {
                this._observer.observe(this.$refs.listContainer, {
                    childList: true
                });
            }
        },

        destroy() {
            this._observer?.disconnect();
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

    // ==========================================
    // COPY BUTTON
    // ==========================================
    Alpine.data('bsCopyBtn', (config) => ({
        copied: false,
        textVal: config.text,
        targetId: config.target,
        duration: config.duration,
        _timer: null,

        copyToClipboard() {
            let content = this.textVal;
            if (this.targetId) {
                const el = document.getElementById(this.targetId);
                if (el) content = el.value || el.innerText;
            }
            if (!content) return;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(content).then(() => this.triggerSuccess()).catch(e => console.error(e));
            } else {
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
            if (this._timer) clearTimeout(this._timer);
            this._timer = setTimeout(() => this.copied = false, this.duration);
        },
        destroy() {
            if (this._timer) clearTimeout(this._timer);
        }
    }));

    // ==========================================
    // EDITOR (Quill)
    // ==========================================
    Alpine.data('bsEditor', (model) => ({
        content: model,
        config: {},
        editor: null,
        isFocused: false,
        _selectionHandler: null,
        _changeHandler: null,
        _updateContent: null,
        _contentWatcher: null,

        init() {
            if (this.$el.dataset.config) this.config = JSON.parse(this.$el.dataset.config);
            const finalModules = this.config.modules || {};

            this.editor = new Quill(this.$refs.quillEditor, {
                theme: this.config.theme || 'snow',
                placeholder: this.config.placeholder,
                modules: finalModules
            });

            if (this.config.height) this.$refs.quillEditor.style.height = this.config.height;
            if (this.content) this.editor.root.innerHTML = this.content;

            this._selectionHandler = (range) => { this.isFocused = !!range; };
            this.editor.on('selection-change', this._selectionHandler);

            this._updateContent = () => {
                let html = this.editor.root.innerHTML;
                if (html === '<p><br></p>') html = null;
                this.content = html;
            };

            this._changeHandler = this._updateContent;
            if (this.config.debounce > 0) {
                this._changeHandler = this.debounce(this._updateContent, this.config.debounce);
            }
            this.editor.on('text-change', this._changeHandler);

            this._contentWatcher = (newContent) => {
                if (this.isFocused) return;
                if (this.editor.root.innerHTML !== newContent) {
                    this.editor.root.innerHTML = newContent ?? '';
                }
            };
            this.$watch('content', this._contentWatcher);
        },
        destroy() {
            if (!this.editor) return;
            // Events entfernen
            this.editor.off('selection-change', this._selectionHandler);
            this.editor.off('text-change', this._changeHandler);
            this.editor = null;
        },
        debounce(func, wait) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => { func.apply(this, args); }, wait);
            };
        }
    }));

    // ==========================================
    // CONTEXT MENU (Major Fixes Here!)
    // ==========================================
    Alpine.data('bsContextMenu', (targetId) => ({
        isOpen: false,
        isVisible: false,
        position: { x: 0, y: 0 },
        // Referenzen für Event Listener
        _contextHandler: null,
        _windowCloseHandler: null,
        _customEventHandler: null,

        get style() {
            return `position: fixed; top: ${this.position.y}px; left: ${this.position.x}px; z-index: 9999; visibility: ${this.isVisible ? 'visible' : 'hidden'};`;
        },

        init() {
            this.$nextTick(() => {
                const element = document.getElementById(targetId);
                if (element) {
                    // Handler erstellen
                    this._contextHandler = (e) => { e.preventDefault(); this.open(e); };
                    this._windowCloseHandler = (e) => { if (!element.contains(e.target)) this.close(); };

                    // Listeners hinzufügen
                    element.addEventListener('contextmenu', this._contextHandler);
                    window.addEventListener('contextmenu', this._windowCloseHandler);
                }
            });

            // Globalen Custom Listener speichern
            this._customEventHandler = (event) => {
                if (event.detail !== targetId) this.close();
            };
            window.addEventListener('bs-context-menu-opened', this._customEventHandler);
        },

        destroy() {
            const element = document.getElementById(targetId);
            if (element && this._contextHandler) {
                element.removeEventListener('contextmenu', this._contextHandler);
            }
            if (this._windowCloseHandler) {
                window.removeEventListener('contextmenu', this._windowCloseHandler);
            }
            if (this._customEventHandler) {
                window.removeEventListener('bs-context-menu-opened', this._customEventHandler);
            }
        },

        open(event) {
            window.dispatchEvent(new CustomEvent('bs-context-menu-opened', { detail: targetId }));
            this.position.x = event.clientX;
            this.position.y = event.clientY;
            this.isVisible = false;
            this.isOpen = true;

            this.$nextTick(() => {
                const el = this.$refs.menuPanel;
                if (!el) return;
                const height = el.offsetHeight;
                const width = el.offsetWidth;
                if ((this.position.y + height) > window.innerHeight) this.position.y -= height;
                if ((this.position.x + width) > window.innerWidth) this.position.x -= width;
                this.isVisible = true;
            });
        },
        close() { this.isOpen = false; this.isVisible = false; }
    }));

    // ==========================================
    // CONTEXT MENU ITEM
    // ==========================================
    Alpine.data('bsContextMenuItem', (config) => ({
        config: config,
        execute() {
            if (typeof this.close === 'function') this.close();
            if (this.config.confirm && !confirm(this.config.confirmText)) return;
            if (!this.$wire) return;

            if (this.config.dispatch) {
                this.$wire.dispatch(this.config.dispatch, this.config.params);
            } else if (this.config.action) {
                const args = Array.isArray(this.config.params) ? this.config.params : [this.config.params];
                this.$wire[this.config.action](args);
            }
        }
    }));

    // ==========================================
    // SHORTCUT (Major Fixes Here!)
    // ==========================================
    Alpine.data('bsShortcut', (config) => ({
        key: config.key,
        prevent: config.prevent ?? true,
        stop: config.stop ?? false,
        handler: config.handler,
        _keydownHandler: null, // Listener Referenz

        init() {
            this.$nextTick(() => {
                if (this.key) this.registerKeyString(this.key);
                else this.registerFromSlot();
            });
        },
        destroy() {
            // WICHTIG: Globalen Listener entfernen
            if (this._keydownHandler) {
                window.removeEventListener('keydown', this._keydownHandler);
                this._keydownHandler = null;
            }
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
            const mapKey = (k) => ({ 'cmd': 'meta', 'win': 'meta', 'opt': 'alt', 'esc': 'escape', 'del': 'delete' }[k] || k);
            const requiredMods = keys.filter(k => modifiers.includes(k));
            let mainKey = keys.find(k => !modifiers.includes(k));
            if (mainKey) mainKey = mapKey(mainKey);

            // Handler in Variable speichern
            this._keydownHandler = (e) => {
                const check = (mod, eventProp) => requiredMods.some(m => m === mod) === eventProp;
                const ctrl = check('ctrl', e.ctrlKey);
                const alt = check('alt', e.altKey) || check('opt', e.altKey);
                const shift = check('shift', e.shiftKey);
                const meta = check('meta', e.metaKey) || check('cmd', e.metaKey);
                const keyHit = mainKey ? (e.key.toLowerCase() === mainKey) : true;

                if (ctrl && alt && shift && meta && keyHit) {
                    if (this.prevent) e.preventDefault();
                    if (this.stop) e.stopPropagation();
                    this.handler();
                }
            };

            window.addEventListener('keydown', this._keydownHandler);
        }
    }));

    // ==========================================
    // COLOR PICKER
    // ==========================================
    Alpine.data('bsColorPicker', (initialModel) => ({
        textValue: initialModel,
        pickerValue: '#000000',
        init() {
            if (!this.textValue) this.textValue = '#000000';
            this.syncPickerFromText();
            this.$watch('textValue', () => this.syncPickerFromText());
        },
        updateFromPicker(hex) { this.pickerValue = hex; this.textValue = hex; },
        syncPickerFromText() {
            if (!this.textValue) return;
            const converted = this.nameToHex(this.textValue);
            if (converted && converted !== '#000000') this.pickerValue = converted;
            else if (this.isBlack(this.textValue)) this.pickerValue = '#000000';
        },
        nameToHex(color) {
            const ctx = document.createElement('canvas').getContext('2d');
            ctx.fillStyle = color; return ctx.fillStyle;
        },
        isBlack(val) {
            const v = val.toLowerCase().trim();
            return v === 'black' || v === '#000' || v === '#000000' || v === 'rgb(0,0,0)';
        }
    }));

    // ==========================================
    // AUTOCOMPLETE
    // ==========================================
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
            this.$nextTick(() => {
                if (this.isStatic) this.loadStaticItems();
                else this.setupLivewireObserver();
            });
        },
        destroy() {
            if (this.observer) {
                this.observer.disconnect();
                this.observer = null;
            }
        },
        setupLivewireObserver() {
            const dropdown = this.$refs.livewireDropdown;
            if (!dropdown) {
                setTimeout(() => this.setupLivewireObserver(), 100);
                return;
            }
            this.observer = new MutationObserver((_mutations) => {
                this.$nextTick(() => { this.checkLivewireResults(); });
            });
            this.observer.observe(dropdown, { childList: true, subtree: true });
            this.checkLivewireResults();
        },
        // ... (Restliche Methoden bleiben gleich, enthalten keine Listener)
        checkLivewireResults() {
            const items = this.$refs.livewireResults?.querySelectorAll('li') || [];
            const count = items.length;
            this.filteredCount = count;
            if (count > 0 && this.meetsMinChars()) {
                if (document.activeElement === this.$refs.input || this.pendingOpen) {
                    if (!this.isExactMatch()) { this.isOpen = true; this.highlightIndex = 0; }
                    this.pendingOpen = false;
                }
            } else if (count === 0 && !this.isOpen) {
                if (this.meetsMinChars() && document.activeElement === this.$refs.input) this.pendingOpen = true;
            }
        },
        loadStaticItems() {
            if (!this.$refs.staticItems) return;
            const items = this.$refs.staticItems.querySelectorAll('[data-autocomplete-item]');
            this.staticItems = Array.from(items).map((el, index) => ({
                element: el, value: el.dataset.value, label: el.dataset.label, index: index, visible: true, visibleIndex: index
            }));
            this.filteredCount = this.staticItems.length;
        },
        meetsMinChars() {
            if (this.isStatic) return this.searchTerm.length >= this.minChars;
            const value = this.$refs.input?.value || ''; return value.length >= this.minChars;
        },
        isExactMatch() {
            const inputValue = this.isStatic ? this.searchTerm.trim() : this.$refs.input?.value.trim() || '';
            if (!inputValue) return false;
            if (this.isStatic) return this.staticItems.some(item => item.label.toLowerCase() === inputValue.toLowerCase());
            const items = this.$refs.livewireResults?.querySelectorAll('[data-display]') || [];
            return Array.from(items).some(item => item.dataset.display.toLowerCase() === inputValue.toLowerCase());
        },
        onFocus() {
            if (this.isExactMatch()) return;
            if (!this.isStatic) { this.pendingOpen = true; this.checkLivewireResults(); }
            else { if (this.meetsMinChars()) this.openDropdown(); }
        },
        onInputChange() {
            if (this.isStatic) {
                if (this.meetsMinChars()) this.filterStaticItems(); else this.closeDropdown();
            } else {
                if (this.meetsMinChars()) this.pendingOpen = true;
                else { this.closeDropdown(); this.pendingOpen = false; }
            }
        },
        filterStaticItems() {
            const term = this.searchTerm.toLowerCase().trim();
            let visibleCount = 0;
            this.staticItems.forEach((item) => {
                const matches = item.label.toLowerCase().includes(term);
                item.visible = matches;
                item.element.style.display = matches ? '' : 'none';
                if (matches) { item.visibleIndex = visibleCount; visibleCount++; }
            });
            this.filteredCount = visibleCount;
            this.highlightIndex = visibleCount > 0 ? 0 : -1;
            if (visibleCount > 0 && this.meetsMinChars() && !this.isExactMatch()) this.isOpen = true;
            else if (this.isExactMatch()) this.isOpen = false;
        },
        openDropdown() {
            if (!this.meetsMinChars() || this.isExactMatch()) return;
            if (this.isStatic && this.staticItems.length === 0) this.loadStaticItems();
            if (this.isStatic) this.filterStaticItems();
            this.isOpen = true; this.highlightIndex = 0;
        },
        closeDropdown() { this.isOpen = false; this.highlightIndex = -1; this.pendingOpen = false; },
        onArrowDown() {
            if (!this.isOpen) { if (this.meetsMinChars() && !this.isExactMatch()) this.openDropdown(); return; }
            if (this.highlightIndex < this.filteredCount - 1) { this.highlightIndex++; this.scrollToHighlighted(); }
            else { this.highlightIndex = 0; this.scrollToHighlighted(); }
        },
        onArrowUp() {
            if (!this.isOpen) return;
            if (this.highlightIndex > 0) { this.highlightIndex--; this.scrollToHighlighted(); }
            else { this.highlightIndex = this.filteredCount - 1; this.scrollToHighlighted(); }
        },
        scrollToHighlighted() {
            this.$nextTick(() => {
                let highlightedEl;
                if (this.isStatic) {
                    const visibleItems = this.staticItems.filter(item => item.visible);
                    const item = visibleItems[this.highlightIndex];
                    if (item) highlightedEl = item.element.querySelector('a');
                } else {
                    highlightedEl = this.$refs.livewireDropdown?.querySelector(`[data-index='${this.highlightIndex}']`);
                }
                if (highlightedEl) highlightedEl.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            });
        },
        onEnter() {
            if (!this.isOpen || this.highlightIndex < 0) return;
            if (this.isStatic) {
                const visibleItems = this.staticItems.filter(item => item.visible);
                const item = visibleItems[this.highlightIndex];
                if (item) this.selectStaticItem(item);
            } else {
                let el = this.$refs.livewireDropdown?.querySelector(`[data-index='${this.highlightIndex}']`);
                if (!el) el = this.$refs.livewireResults?.querySelector(`li:nth-child(${this.highlightIndex + 1}) a[data-index]`);
                if (el) this.selectItem(el);
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
            if (item && item.visible) this.highlightIndex = item.visibleIndex;
        },
        selectFromStatic(linkEl) {
            const li = linkEl.closest('[data-autocomplete-item]');
            const item = this.staticItems.find(i => i.element === li);
            if (item) this.selectStaticItem(item);
        },
        selectStaticItem(item) {
            this.searchTerm = item.label;
            if (this.$wire && this.selectModel) this.$wire.set(this.selectModel, item.value);
            this.closeDropdown();
            this.$dispatch('autocomplete-selected', { value: item.value, display: item.label });
        },
        selectItem(el) {
            const selectValue = el.dataset.select;
            const displayValue = el.dataset.display;
            if (this.$wire) {
                if (this.selectModel) this.$wire.set(this.selectModel, selectValue);
                if (this.searchModel) this.$wire.set(this.searchModel, displayValue);
            }
            this.closeDropdown();
            this.$dispatch('autocomplete-selected', { value: selectValue, display: displayValue });
        }
    }));

    // ==========================================
    // CODE BLOCK (Copy/Parse Logic)
    // ==========================================
    Alpine.data('bsCodeBlock', (config) => ({
        currentHtml: config.initialHtml,
        currentRaw: config.initialRaw,
        prettyHtml: config.prettyHtml,
        minifiedHtml: config.minifiedHtml,
        rawPretty: config.rawPretty,
        rawMinified: config.rawMinified,
        isJson: config.isJson,
        isPretty: false,
        showLineNumbers: config.showLineNumbers,

        marks: new Set(
            (Array.isArray(config.lineMarks) ? config.lineMarks : String(config.lineMarks).split(','))
                .map(val => parseInt(val)).filter(val => !isNaN(val))
        ),

        get lineCount() {
            if (!this.currentRaw) return 0;
            return this.currentRaw.split(/\r\n|\r|\n/).length;
        },
        isMarked(lineNumber) { return this.marks.has(lineNumber); },
        toggleJson() {
            if (!this.isJson) return;
            this.isPretty = !this.isPretty;
            if (this.isPretty) {
                this.currentHtml = this.prettyHtml; this.currentRaw = this.rawPretty;
            } else {
                this.currentHtml = this.minifiedHtml; this.currentRaw = this.rawMinified;
            }
        }
    }));
}


function registerAlpineDirective() {
    Alpine.directive('bs-tooltip', (el, { expression }, { evaluate, cleanup }) => {
        let title = evaluate(expression);
        if (!title) return;
        Array.from(el.children).forEach(element => element.removeAttribute('title'));

        let tooltip = new bootstrap.Tooltip(el, {
            title: title,
            placement: el.dataset.bsPlacement || 'top',
            customClass: el.dataset.bsCustomClass || '',
            trigger: 'hover focus',
            html: false
        });

        cleanup(() => {
            tooltip.dispose();
        });
    });

    Alpine.directive('bs-popover', (el, { expression }, { evaluate, cleanup }) => {
        let content = expression ? evaluate(expression) : null;
        const popover = new bootstrap.Popover(el, {
            content: content || el.dataset.bsContent,
            title: el.dataset.bsTitle || '',
            placement: el.dataset.bsPlacement || 'right',
            customClass: el.dataset.bsCustomClass || '',
            trigger: el.dataset.bsTrigger || 'focus',
            container: 'body',
            html: false
        });

        cleanup(() => {
            popover.dispose();
        });
    });
}
