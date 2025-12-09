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
                Livewire.hook('commit', ({ component, succeed }) => {
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

    Alpine.data('bsModal', (id, wireModel = null) => ({
        modalInstance: null,

        init() {
            const el = this.$el;

            // 1. Bootstrap Modal Instanz erstellen
            this.modalInstance = new bootstrap.Modal(el);

            // 2. Listener für "Wire:Model" (Server steuert Modal)
            if (wireModel !== null) {
                this.$watch('wireModel', (value) => {
                    if (value) this.modalInstance.show();
                    else this.modalInstance.hide();
                });
            }

            // 3. Listener für Service Events (Modal::open('id'))
            // Wir hören auf das window event
            window.addEventListener('bs-modal-show', (event) => {
                if (event.detail.id === id) {
                    this.modalInstance.show();
                }
            });

            window.addEventListener('bs-modal-hide', (event) => {
                if (event.detail.id === id) {
                    this.modalInstance.hide();
                }
            });

            // 4. Bootstrap Events an Livewire zurückmelden
            // Wenn User ESC drückt oder Backdrop klickt -> Wire Model auf false setzen
            el.addEventListener('hidden.bs.modal', () => {
                if (wireModel !== null) {
                    this.wireModel = false;
                }
            });

            el.addEventListener('shown.bs.modal', () => {
                if (wireModel !== null) {
                    this.wireModel = true;
                }
            });
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
