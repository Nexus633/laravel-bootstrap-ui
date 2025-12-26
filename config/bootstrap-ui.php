<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Script Loading Configuration
    |--------------------------------------------------------------------------
    |
    | Hier kannst du steuern, welche optionalen Bibliotheken (Assets)
    | global auf JEDER Seite geladen werden sollen.
    |
    | Performance-Tipp: Lasse diese Werte auf 'false'. Lade die Skripte nur
    | auf den Seiten, wo du sie wirklich brauchst, indem du die Attribute
    | in der Script-Komponente nutzt: <x-bs::scripts charts />
    |
    */
    'scripts' => [

        /*
        |--------------------------------------------------------------------------
        | Charts (Chart.js)
        |--------------------------------------------------------------------------
        |
        | Soll die Chart.js Bibliothek global geladen werden?
        | false = Nur laden, wenn <x-bs::scripts charts="true" /> gesetzt ist.
        |
        */
        'charts' => false,

        /*
        |--------------------------------------------------------------------------
        | Editor (WYSIWYG)
        |--------------------------------------------------------------------------
        |
        | Sollen die Editor-Assets (CSS/JS für Quill) global geladen werden?
        | false = Nur laden, wenn <x-bs::scripts editor="true" /> gesetzt ist.
        |
        */
        'editor' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Toast Configuration
    |--------------------------------------------------------------------------
    |
    | Hier kannst du das Standardverhalten der Toast-Benachrichtigungen
    | (temporäre Popups) konfigurieren. Diese Einstellungen gelten global
    | für alle Toasts, sofern sie nicht beim Aufruf überschrieben werden.
    |
    */
    'toast' => [

        /*
        |--------------------------------------------------------------------------
        | Position
        |--------------------------------------------------------------------------
        |
        | Wo sollen Toasts standardmäßig erscheinen?
        | Optionen: top-end, top-start, top-center, bottom-end, bottom-start, bottom-center
        |
        */
        'position' => 'bottom-center',

        /*
        |--------------------------------------------------------------------------
        | Duration (Dauer)
        |--------------------------------------------------------------------------
        |
        | Wie lange sollen sie sichtbar bleiben (in Millisekunden)?
        | Setze 0, um Toasts nur manuell schließbar zu machen.
        |
        */
        'duration' => 5000,

        /*
        |--------------------------------------------------------------------------
        | Animate
        |--------------------------------------------------------------------------
        |
        | Sollen Animationen (Fade In/Out) aktiv sein?
        |
        */
        'animate' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Flash Configuration
    |--------------------------------------------------------------------------
    |
    | Hier konfigurierst du die Flash-Benachrichtigungen (Session Alerts).
    | Im Gegensatz zu Toasts sind diese oft prominenter (z.B. oben im Content)
    | und werden typischerweise nach Redirects oder Formular-Aktionen angezeigt.
    |
    */
    'flash' => [

        /*
        |--------------------------------------------------------------------------
        | Auto Dismiss
        |--------------------------------------------------------------------------
        |
        | Sollen Flash-Nachrichten automatisch verschwinden?
        | 0 = Nein (Manuelles Schließen), >0 = Zeit in Millisekunden (z.B. 3000).
        |
        */
        'auto_dismiss' => 3000,

        /*
        |--------------------------------------------------------------------------
        | Animate
        |--------------------------------------------------------------------------
        |
        | Sollen Animationen (Fade In/Out) beim Einblenden und Entfernen
        | aktiv sein?
        |
        */
        'animate' => true,

        /*
        |--------------------------------------------------------------------------
        | Animation Classes
        |--------------------------------------------------------------------------
        |
        | Hier definierst du die CSS-Klassen für die Übergangseffekte.
        | Diese werden von Alpine.js oder CSS Transitions genutzt.
        |
        | Standard:
        |   'enter' => 'alert-enter',   // Beim Erscheinen
        |   'leave' => 'alert-hiding',  // Beim Verschwinden
        |
        */
        'animate_class' => [
            'enter' => 'alert-enter',
            'leave' => 'alert-hiding',
        ]
    ],

    /*
     |--------------------------------------------------------------------------
     | Icon Configuration
     |--------------------------------------------------------------------------
     |
     | Hier definierst du das global verwendete Icon-Set für alle Komponenten
     | (z.B. <x-bs::icon>, Buttons, Alerts). Standardmäßig ist das Paket
     | auf Bootstrap Icons konfiguriert, unterstützt aber auch FontAwesome u.a.
     |
     */
    'icons' => [

        /*
        |--------------------------------------------------------------------------
        | Base Class
        |--------------------------------------------------------------------------
        |
        | Die Basis-CSS-Klasse, die jedes Icon-Element (<i>) erhält.
        | Beispiele: 'bi' (Bootstrap), 'fas' (FontAwesome Solid).
        |
        */
        'base'   => 'bi',

        /*
        |--------------------------------------------------------------------------
        | Icon Prefix
        |--------------------------------------------------------------------------
        |
        | Der Präfix, der automatisch vor den Icon-Namen gesetzt wird.
        | Beispiel: Bei 'bi-' wird aus name="heart" die Klasse "bi-heart".
        |
        */
        'prefix' => 'bi-',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tooltip Configuration
    |--------------------------------------------------------------------------
    |
    | Hier konfigurierst du die globalen Standardwerte für Tooltips.
    | Diese kleinen Hinweis-Boxen erscheinen beim Hovern über Elementen
    | und können bezüglich Position und Design hier angepasst werden.
    |
    */
    'tooltip' => [

        /*
        |--------------------------------------------------------------------------
        | Default Position
        |--------------------------------------------------------------------------
        |
        | Wo soll der Tooltip standardmäßig erscheinen?
        | Optionen: 'top', 'bottom', 'left', 'right', 'auto'
        |
        */
        'position' => 'top',

        /*
        |--------------------------------------------------------------------------
        | Custom CSS Class
        |--------------------------------------------------------------------------
        |
        | Eine globale CSS-Klasse, die jedem Tooltip hinzugefügt wird.
        | Nützlich für Theming via Bootstrap SASS-Variablen (z.B. 'custom-tooltip').
        |
        */
        'custom_class' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Popover Configuration
    |--------------------------------------------------------------------------
    |
    | Hier konfigurierst du die globalen Standardwerte für Popovers.
    | Diese sind erweiterte Overlay-Elemente (ähnlich wie Tooltips, aber
    | für größere Inhalte), die meist per Klick aktiviert werden.
    |
    */
    'popover' => [

        /*
        |--------------------------------------------------------------------------
        | Default Position
        |--------------------------------------------------------------------------
        |
        | Wo soll das Popover standardmäßig erscheinen?
        | Optionen: 'top', 'bottom', 'left', 'right', 'auto'
        |
        */
        'position' => 'right',

        /*
        |--------------------------------------------------------------------------
        | Custom CSS Class
        |--------------------------------------------------------------------------
        |
        | Eine globale CSS-Klasse, die jedem Popover hinzugefügt wird.
        | Nützlich für Theming via Bootstrap SASS-Variablen (z.B. 'custom-popover').
        |
        */
        'custom_class' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination Configuration
    |--------------------------------------------------------------------------
    |
    | Hier definierst du das globale Erscheinungsbild der Paginierung,
    | wenn der Trait WithBootstrapUiPagination verwendet wird. Du kannst
    | steuern, ob Infos, Icons oder Sprungtasten angezeigt werden sollen.
    |
    */
    'pagination' => [

        /*
        |--------------------------------------------------------------------------
        | Show First & Last Page
        |--------------------------------------------------------------------------
        |
        | Sollen Buttons für die allererste (<<) und allerletzte (>>) Seite
        | angezeigt werden?
        |
        */
        'show_first_and_last_page' => false,

        /*
        |--------------------------------------------------------------------------
        | Show Summary
        |--------------------------------------------------------------------------
        |
        | Soll der Info-Text "Zeige 1 bis 10 von 50 Ergebnissen" links neben
        | der Navigation angezeigt werden?
        |
        */
        'show_summary' => true,

        /*
        |--------------------------------------------------------------------------
        | Use Icons
        |--------------------------------------------------------------------------
        |
        | Sollen Pfeile (‹ ›) statt Text (Zurück / Weiter) für die Navigation
        | genutzt werden?
        |
        */
        'use_icons' => true,
    ],
    /*
    |--------------------------------------------------------------------------
    | Editor Configuration (Quill.js)
    |--------------------------------------------------------------------------
    |
    | Hier konfigurierst du das Verhalten und Aussehen des WYSIWYG-Editors.
    | Diese Einstellungen werden an die Quill.js Instanz übergeben.
    |
    */
    'editor' => [
        /*
        |--------------------------------------------------------------------------
        | Theme
        |--------------------------------------------------------------------------
        |
        | Das Design des Editors.
        | 'snow'   = Klassische Toolbar oben.
        | 'bubble' = Floating Toolbar (erscheint beim Markieren von Text).
        |
        */
        'theme' => 'snow',

        /*
        |--------------------------------------------------------------------------
        | Modules
        |--------------------------------------------------------------------------
        |
        | Konfiguration der aktiven Module (Toolbar, History, Syntax).
        |
        */
        'modules' => [
            /*
            |--------------------------------------------------------------------------
            | Toolbar
            |--------------------------------------------------------------------------
            |
            | Definiert, welche Buttons in der Leiste verfügbar sind.
            |
            */
            'toolbar' => [
                // Gruppe 1: Text-Style (Fett, Kursiv, Unterstrichen, Durchgestrichen)
                ['bold', 'italic', 'underline', 'strike'],

                // Gruppe 2: Zitate & Code-Blöcke
                ['blockquote', 'code-block'],

                // Gruppe 3: Medien (Link, Bild, Video, Formel) - Standardmäßig deaktiviert
                //['link', 'image', 'video', 'formula'],

                // Gruppe 4: Header (H1, H2 Buttons)
                [['header' => 1], ['header' => 2]],

                // Gruppe 5: Listen (Nummeriert, Bullet, Checkbox)
                [['list' => 'ordered'], ['list' => 'bullet'], ['list' => 'check']],

                // Gruppe 6: Script (Hoch/Tiefgestellt)  - Standardmäßig deaktiviert
                //[['script' => 'sub'], ['script' => 'super']],

                // Gruppe 7: Einrückung (Indent)  - Standardmäßig deaktiviert
                //[['indent' => '-1'], ['indent' => '+1']],

                // Gruppe 8: Textrichtung (RTL)  - Standardmäßig deaktiviert
                //[['direction' => 'rtl']],

                // Gruppe 9: Größen (Dropdown)  - Standardmäßig deaktiviert
                //[['size' => ['small', false, 'large', 'huge']]],

                // Gruppe 10: Header (Dropdown Auswahl)
                [['header' => [1, 2, 3, 4, 5, 6, false]]],

                // Gruppe 11: Farben, Schriftart & Ausrichtung  - Standardmäßig deaktiviert
                /*
                [
                    ['color' => []],
                    ['background' => []]
                ],
                */
                //[['font' => []]],
                [['align' => []]],

                // Gruppe 12: Formatierung entfernen
                ['clean']
            ],

            /*
            |--------------------------------------------------------------------------
            | History (Undo/Redo)
            |--------------------------------------------------------------------------
            */
            'history' => [
                // Zeit in ms, bis Inputs gruppiert werden
                'delay'    => 2000,
                // Maximale Anzahl an Undo-Schritten
                'maxStack' => 500,
                // Nur User-Input tracken (keine API-Changes)
                'userOnly' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Syntax Highlighting
            |--------------------------------------------------------------------------
            |
            | Aktiviert die Syntax-Hervorhebung für Code-Blöcke.
            | WICHTIG: Das Highlight.js Skript muss global geladen sein!
            | Kann Highlight.js nicht geladen werden, so wird es automatisch deaktiviert
            |
            */
            'syntax' => true
        ],

        /*
        |--------------------------------------------------------------------------
        | Syntax Highlight Theme (CSS)
        |--------------------------------------------------------------------------
        |
        | Definiert, welches CSS-Theme für highlight.js geladen werden soll.
        | Beliebte Optionen: 'atom-one-dark', 'github', 'monokai', 'dracula'.
        | Die Endung .min.css ist erforderlich.
        |
        | Weitere Themes: https://github.com/highlightjs/highlight.js/tree/main/src/styles
        | Pfad: resources/assets/css/editor/highlight
        |
        */
        'highlight' => [
            'theme' => 'atom-one-dark.min.css'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Tree View Configuration (Dateibaum)
    |--------------------------------------------------------------------------
    |
    | Hier konfigurierst du die Darstellung der Tree-View-Komponente.
    | Du kannst die Icons für Ordner, das Standard-Datei-Icon sowie
    | die Zuordnung von Dateiendungen zu spezifischen Icons festlegen.
    |
    */
    'tree_view' => [

        /*
        |--------------------------------------------------------------------------
        | Folder Icons
        |--------------------------------------------------------------------------
        |
        | Definiert die Icons für Ordner-Elemente.
        | 'closed' = Icon, wenn der Ordner zugeklappt ist.
        | 'open'   = Icon, wenn der Ordner ausgeklappt ist.
        |
        */
        'folder' => [
            'closed' => 'folder-fill',
            'open'   => 'folder2-open',
        ],

        /*
        |--------------------------------------------------------------------------
        | Default File Icon
        |--------------------------------------------------------------------------
        |
        | Das Fallback-Icon für Dateien, deren Dateiendung (Extension)
        | nicht in der 'extension_map' gefunden wird.
        |
        */
        'default_file_icon' => 'file-earmark',

        /*
        |--------------------------------------------------------------------------
        | File Extension Mapping
        |--------------------------------------------------------------------------
        |
        | Hier ordnest du Dateiendungen einem Bootstrap-Icon zu.
        | Format: 'icon-name' => ['ext1', 'ext2', ...]
        |
        | Der Service wandelt dies intern um, sodass eine sehr schnelle
        | Zuordnung (O(1)) beim Rendern möglich ist.
        |
        */
        'extension_map' => [
            // Dokumente (PDF)
            'file-earmark-pdf'    => ['pdf'],

            // Bilder
            'file-earmark-image'  => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'tiff'],

            // Archive / Komprimiert
            'file-earmark-zip'    => ['zip', 'rar', '7z', 'tar', 'gz', 'bz2'],

            // Textdateien
            'file-earmark-text'   => ['txt', 'md', 'log', 'ini', 'cfg', 'env'],

            // Tabellenkalkulation
            'file-earmark-excel'  => ['xls', 'xlsx', 'csv', 'ods'],

            // Textverarbeitung
            'file-earmark-word'   => ['doc', 'docx', 'rtf', 'odt'],

            // Präsentationen
            'file-earmark-slides' => ['ppt', 'pptx', 'odp'],

            // Videos
            'file-earmark-play'   => ['mp4', 'avi', 'mov', 'mkv', 'webm', 'wmv'],

            // Audio
            'file-earmark-music'  => ['mp3', 'wav', 'ogg', 'flac', 'm4a'],

            // Code / Entwicklung
            'file-earmark-code'   => ['php', 'js', 'css', 'html', 'json', 'xml', 'sql', 'yaml', 'yml', 'vue', 'blade.php'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shortcut Configuration
    |--------------------------------------------------------------------------
    |
    */
    'shortcuts' => [
        /*
        |--------------------------------------------------------------------------
        | Shortcut Security
        |--------------------------------------------------------------------------
        |
        | Hier werden die Sicherheitsmechanismen für die Shortcut-Komponente
        | konfiguriert. Da Shortcuts JS-Code ausführen können, verhindern wir
        | hier Injection-Angriffe und unsicheren Code.
        |
        */
        'security' => [
            /*
            |--------------------------------------------------------------------------
            | Enabled
            |--------------------------------------------------------------------------
            |
            | Schaltet den Sicherheits-Check global an oder aus.
            | Wenn false, wird jeder Code ungeprüft ausgeführt (Nicht empfohlen).
            |
            */
            'enabled' => true,

            /*
            |--------------------------------------------------------------------------
            | Exception Handling
            |--------------------------------------------------------------------------
            |
            | Bestimmt, wie mit Sicherheitsverstößen umgegangen wird.
            | true  = Wirft eine PHP Exception (App Crash). Ideal für 'local'.
            | false = Loggt nur eine Warnung und blockiert den Code. Ideal für 'production'.
            |
            */
            'throw_exception' => env('APP_DEBUG', false),

            /*
            |--------------------------------------------------------------------------
            | Blacklist
            |--------------------------------------------------------------------------
            |
            | Eine Liste von Schlüsselwörtern oder Mustern, die im JS-Code der
            | Shortcuts verboten sind. Groß-/Kleinschreibung wird ignoriert.
            |
            */
            'blacklist' => [
                // Gruppe 1: Code Ausführung & Manipulation
                'eval',
                'exec',
                'Function',       // new Function(...)
                'setTimeout',     // Oft für Hacks genutzt
                'setInterval',

                // Gruppe 2: Netzwerk & Exfiltration
                'XMLHttpRequest',
                'fetch',
                'navigator.sendBeacon',

                // Gruppe 3: Speicher & Cookies (Stealing)
                'document.cookie',
                'localStorage',
                'sessionStorage',
                'indexedDB',

                // Gruppe 4: Navigation & Redirects
                'window.location',
                'history.pushState',

                // Gruppe 5: HTML Injection
                '<script',
                'innerHTML',
                'outerHTML',

                // Gruppe 6: System (PHP exec via JS hacks etc.)
                'system',
                'passthru',
                'shell_exec',
            ],
        ],

    ],
];
