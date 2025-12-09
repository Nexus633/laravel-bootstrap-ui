(function() {
    // Helper Funktionen für LocalStorage
    const getStoredTheme = () => localStorage.getItem('theme');
    const setStoredTheme = theme => localStorage.setItem('theme', theme);

    // Wir prüfen, ob der Server (PHP) via Session ein Theme erzwungen hat.
    // Das Attribut 'data-server-theme' wird von <x-bs::head> gesetzt.
    const serverSessionTheme = document.documentElement.getAttribute('data-server-theme');

    const getPreferredTheme = () => {
        // 1. Priorität: Hat der Server was befohlen? (z.B. via ThemeSwitch::darkMode())
        if (serverSessionTheme) {
            return serverSessionTheme;
        }

        // 2. Priorität: Hat der User schon mal was eingestellt?
        const storedTheme = getStoredTheme();
        if (storedTheme) {
            return storedTheme;
        }

        // 3. Priorität: Was sagt das Betriebssystem (Windows/Mac Darkmode)?
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const setTheme = theme => {
        if (theme === 'auto') {
            document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme);
        }
    };

    // --- INITIALISIERUNG ---
    // Sofort ausführen, damit kein weißes Flackern entsteht
    setTheme(getPreferredTheme());

    // Wenn der Server das Theme erzwungen hat, speichern wir es jetzt auch lokal,
    // damit es beim nächsten Klick/Reload erhalten bleibt.
    if (serverSessionTheme) {
        setStoredTheme(serverSessionTheme);
    }

    // --- GLOBALE FUNKTION ---
    // Diese Funktion wird vom <x-bs::theme-toggle> Button aufgerufen
    window.toggleBootstrapTheme = function() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        setStoredTheme(newTheme);
        setTheme(newTheme);
        return newTheme;
    }

    document.addEventListener('alpine:init', () => {

    });

})();

class BootstrapColor {
    static getBaseColor(name) {
        return getComputedStyle(document.documentElement)
            .getPropertyValue(`--bs-${name}`)
            .trim();
    }

    static hexWithAlpha(hex, alpha = 1) {
        // falls hex wie "#0d6efd" ist → alpha als HEX anhängen
        const a = Math.round(alpha * 255).toString(16).padStart(2, '0');
        return hex + a;
    }

    static get(name, alpha = 1) {
        const base = this.getBaseColor(name);
        return alpha === 1 ? base : this.hexWithAlpha(base, alpha);
    }
}

window.bsChart = function(labels, datasets, options) {
    let _chartInstance = null;
    return {
        dataLabels: labels,
        dataSets: datasets,
        chartOptions: options,

        init() {
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js missing.');
                return;
            }
            this.$nextTick(() => { this.render(); });
        },

        resolveColor(value) {
            // ... (dein Code bleibt gleich) ...
            if (typeof value === 'string' && value.startsWith('bs:')) {
                const parts = value.split(':');
                const color = parts[1];
                const alpha = parts[2] ? parseFloat(parts[2]) : 1;
                if (window.BootstrapColor) {
                    return window.BootstrapColor.get(color, alpha);
                }
                return '#6c757d';
            }
            return value;
        },

        parseData(originalData) {
            // ... (dein Code bleibt gleich) ...
            let parsed = JSON.parse(JSON.stringify(originalData));
            parsed.forEach(dataset => {
                ['backgroundColor', 'borderColor', 'pointBackgroundColor', 'pointBorderColor'].forEach(prop => {
                    if (dataset[prop]) {
                        if (Array.isArray(dataset[prop])) {
                            dataset[prop] = dataset[prop].map(v => this.resolveColor(v));
                        } else {
                            dataset[prop] = this.resolveColor(dataset[prop]);
                        }
                    }
                });
            });
            return parsed;
        },

        render() {
            if (typeof Chart === 'undefined') return;

            // Zugriff auf die lokale Variable statt this.chart
            if (_chartInstance) {
                _chartInstance.destroy();
                _chartInstance = null;
            }

            const ctx = this.$refs.canvas.getContext('2d');
            const type = this.$root.getAttribute('data-type') || 'line';

            // Deine Hilfsfunktion nutzen (ich habe sie hier gekürzt, nutz deine volle Version)
            const finalDatasets = this.parseData(this.dataSets);

            // Chart erstellen und in die lokale Variable speichern
            // Kein Alpine.raw nötig, weil die Variable 'invisible' für Alpine ist.
            _chartInstance = new Chart(ctx, {
                type: type,
                data: {
                    labels: this.dataLabels,
                    datasets: finalDatasets
                },
                options: {
                    ...this.chartOptions,
                    // Sicherheitsnetz: Tooltips explizit erzwingen
                    plugins: {
                        tooltip: { enabled: true }
                    }
                }
            });
        },
        destroy() {
            if (_chartInstance) {
                console.log('Chart cleaning up...'); // Optional zum Testen
                _chartInstance.destroy();
                _chartInstance = null;
            }
        }
    };
};
window.BootstrapColor = BootstrapColor;
