<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Toast Configuration
    |--------------------------------------------------------------------------
    */
    'toast' => [
        /*
        |--------------------------------------------------------------------------
        | position
        |--------------------------------------------------------------------------
        |
        | Wo sollen Toasts standardmäßig erscheinen?
        | Optionen: top-end, top-start, top-center, bottom-end, bottom-start, bottom-center
        |
        */
        'position' => 'bottom-center',
        /*
        |--------------------------------------------------------------------------
        | duration
        |--------------------------------------------------------------------------
        |
        | Wie lange sollen sie sichtbar bleiben (in Millisekunden)?
        |
        */
        'duration' => 5000,
        /*
        |--------------------------------------------------------------------------
        | animate
        |--------------------------------------------------------------------------
        |
        | Sollen Animationen (Fade in/Out) aktiv sein?
        |
        */
        'animate' => true,
    ],
    /*
    |--------------------------------------------------------------------------
    | Flash Configuration
    |--------------------------------------------------------------------------
    */
    'flash' => [
        /*
        |--------------------------------------------------------------------------
        | auto_dismiss
        |--------------------------------------------------------------------------
        |
        | Sollen Flash-Nachrichten (Alerts) automatisch verschwinden?
        | 0 = Nein, >0 = Millisekunden (z.B. 5000)
        |
        */
        'auto_dismiss' => 3000,
        /*
        |--------------------------------------------------------------------------
        | animate
        |--------------------------------------------------------------------------
        |
        | Sollen Animationen (Fade in/Out) aktiv sein?
        |
        */
        'animate' => true,
        /*
        |--------------------------------------------------------------------------
        | animate_class
        |--------------------------------------------------------------------------
        |
        | Welche Klasse soll für die Animationen verwendet werden?
        | Es können mehrere Klassen definiert werden.
        |
        | Standard:
        |   'enter' => 'alert-enter',
        |   'leave' => 'alert-hiding',
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
    | Hier kannst du das Icon-Set definieren.
    |
    | Bootstrap Icons:
    |   'base' => 'bi',    // Die Basis-Klasse
    |   'prefix' => 'bi-', // Der Prefix vor dem Icon-Namen
    |
    | FontAwesome Solid (Free):
    |   'base' => 'fas',
    |   'prefix' => 'fa-',
    |
    | FontAwesome Regular:
    |   'base' => 'far',
    |   'prefix' => 'fa-',
    |
    */
    'icons' => [
        'base'   => 'bi',   // Standard: Bootstrap Icons
        'prefix' => 'bi-',  // Standard Prefix
    ],
    /*
    |--------------------------------------------------------------------------
    | Icon Configuration
    |--------------------------------------------------------------------------
    |
    */
    'tooltip' => [
        /*
        |--------------------------------------------------------------------------
        | Standard Positionierung
        |--------------------------------------------------------------------------
        |
        | Optionen: 'top', 'bottom', 'left', 'right', 'auto'
        |
        */
        'position' => 'top',
        /*
        |--------------------------------------------------------------------------
        | Custom CSS Klasse
        |--------------------------------------------------------------------------
        |
        | Eine globale CSS-Klasse, die allen Tooltips hinzugefügt wird
        | (z.B. für eigene Farben via Bootstrap 'custom-tooltip' SASS Variablen).
        |
        */
        'custom_class' => '',
    ],
    'popover' => [
        /*
        |--------------------------------------------------------------------------
        | Standard Positionierung
        |--------------------------------------------------------------------------
        |
        | Optionen: 'top', 'bottom', 'left', 'right', 'auto'
        |
        */
        'position' => 'right',
        /*
        |--------------------------------------------------------------------------
        | Custom CSS Klasse
        |--------------------------------------------------------------------------
        |
        | Eine globale CSS-Klasse, die allen Popover hinzugefügt wird
        | (z.B. für eigene Farben via Bootstrap 'custom-popover' SASS Variablen).
        |
        */
        'custom_class' => '',
    ],
];
