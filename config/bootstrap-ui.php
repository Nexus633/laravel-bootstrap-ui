<?php
return [
    'toast' => [
        /*
         * Wo sollen Toasts standardmäßig erscheinen?
         * Optionen: top-end, top-start, top-center, bottom-end, bottom-start, bottom-center
         */
        'position' => 'bottom-center',

        /*
         * Wie lange sollen sie sichtbar bleiben (in Millisekunden)?
         */
        'duration' => 5000,

        /*
         * Sollen Animationen (Fade in/Out) aktiv sein?
         */
        'animate' => true,
    ],

    'flash' => [
        /*
         * Sollen Flash-Nachrichten (Alerts) automatisch verschwinden?
         * 0 = Nein, >0 = Millisekunden (z.B. 5000)
         */
        'auto_dismiss' => 3000,

        /*
         * Sollen Animationen (Fade in/Out) aktiv sein?
         */
        'animate' => true,

        /*
         * Welche Klasse soll für die Animationen verwendet werden?
         * Es können mehrere Klassen definiert werden.
         */
        'animate_class' => [
            'enter' => 'alert-enter',
            'leave' => 'alert-hiding',
        ]
    ]
];
