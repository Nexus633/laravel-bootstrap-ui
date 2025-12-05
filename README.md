# Laravel Bootstrap UI

Ein modernes, leichtgewichtiges UI-Kit für **Laravel 12** und **Livewire 3**, basierend auf **Bootstrap 5.3**.
Dieses Paket bietet fertige Blade-Komponenten, die "Out of the Box" funktionieren – inklusive Assets, Icons und Dark-Mode Support.

## 🚀 Features

* **Zero-Config Assets:** Bootstrap CSS, JS und Icons werden automatisch bereitgestellt (kein `npm run build` nötig).
* **Livewire Integration:** Buttons mit Lade-Spinnern, Real-Time Validierung bei Inputs.
* **Hybrid Feedback System:** Toasts und Flash-Messages funktionieren sowohl bei Redirects (Session) als auch bei Livewire-Actions (Events) nahtlos.
* **Dark Mode:** Eingebauter Theme-Switch mit `localStorage` Persistenz und "Anti-Flicker" Script.
* **Fluent Layouts:** Grid-System und Container als Blade-Komponenten.

---

## 📦 Installation

Installiere das Paket über Composer:

```bash
  composer require nexus633/laravel-bootstrap-ui
```

### Assets einrichten (Optional)

Das Paket funktioniert sofort über interne Routen (Zero-Config). Für die Produktion empfehlen wir jedoch, die Assets zu veröffentlichen:

```bash
  php artisan vendor:publish --tag=bootstrap-ui-assets
```

### Konfiguration (Optional)

Um Standardwerte für Toasts (Position, Dauer) oder Flash-Messages anzupassen:

```bash
  php artisan vendor:publish --tag=bootstrap-ui-config
```

---

## ⚙️ Setup

Füge die Direktiven und globalen Komponenten in dein Haupt-Layout ein (z.B. `resources/views/components/layouts/app.blade.php`).

```html
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Page Title' }}</title>

    <x-bs::head />
</head>
<body>

    <x-bs::flash />
    <x-bs::toast />

    {{ $slot }}

    <x-bs::scripts />
</body>
</html>
```

---

## 🧩 Komponenten

### Button
Automatische Lade-Spinner bei Livewire-Aktionen und Icon-Support.

```html
<x-bs::button wire:click="save" loading="save">
    Speichern
</x-bs::button>

<x-bs::button variant="danger" icon="bi bi-trash" wire:click="delete">
    Löschen
</x-bs::button>

<x-bs::button href="/home" variant="link">
    Zurück
</x-bs::button>
```

### Formulare (Input)
Automatische Labels, Fehlerbehandlung und Input-Groups.

```html
<x-bs::input
    name="email"
    label="E-Mail Adresse"
    type="email"
    prepend="@"
    wire:model="email"
/>
```

Verwende Icons statt Text
```html
<x-bs::input
    name="email"
    label="E-Mail Adresse"
    type="email"
    icon:prepend="bi bi-envelope"
    wire:model="email"
/>
```

### Formulare (Select)
Akzeptiert Arrays oder Slots.

```html
<x-bs::select
    name="status"
    label="Status"
    :options="['active' => 'Aktiv', 'inactive' => 'Inaktiv']"
    wire:model="status"
/>

<x-bs::select name="category" label="Kategorie" placehole="Bitte wählen...">
    <x-bs::select.option.group label="Elektronik">
        <x-bs::select.option value="tv" content="Fernseher" />
    </x-bs::select.option.group>
</x-bs::select>
```

### Checkboxen, Radios & Switches

```html
<x-bs::check name="agb" label="AGB akzeptieren" wire:model="agb" />

<x-bs::check.switch name="notify" label="Benachrichtigungen an" wire:model="notify" />

<x-bs::check.radio name="gender" value="m" label="Männlich" wire:model="gender" />
<x-bs::check.radio name="gender" value="f" label="Weiblich" wire:model="gender" />
```

### Layout (Container & Grid)

```html
<x-bs::container>
    <x-bs::row g="3">
        <x-bs::col size="12" md="6" lg="4">
            Spalte 1
        </x-bs::col>
        <x-bs::col size="12" md="6" lg="4">
            Spalte 2
        </x-bs::col>
    </x-bs::row>
</x-bs::container>
```

### Theme Toggle (Dark Mode)
Schaltet zwischen Light/Dark Mode um und speichert die Wahl.

```html
<x-bs::theme-toggle />

<x-bs::theme-toggle icon:light="bi bi-sun-fill" icon:dark="bi bi-moon-stars-fill" />
```

---

## 📢 Feedback System (Toasts & Alerts)

Verwende die Facades in deinen Controllern oder Livewire-Komponenten. Das System erkennt automatisch, ob es eine Session-Flash-Message (Redirect) oder ein Browser-Event (Livewire) senden muss.

### Toasts (Popups)
Stapeln sich oben rechts (konfigurierbar).

```php
use Nexus633\BootstrapUi\Facades\Toast;

// Erfolgsmeldung
Toast::success('Benutzer gespeichert!');

// Mit Titel
Toast::error('Verbindung fehlgeschlagen', 'API Fehler');

// Info
Toast::info('Download gestartet...');
```

### Flash (Alerts)
Erscheinen im Layout (wo `<x-bs::flash />` platziert ist).

```php
use Nexus633\BootstrapUi\Facades\Flash;

Flash::success('Profil aktualisiert.');
Flash::warning('Bitte E-Mail bestätigen.', 'Achtung!');
```

---

## 🛠 Konfiguration

Veröffentliche die Config-Datei, um Animationen oder Positionen anzupassen:

```bash
php artisan vendor:publish --tag=bootstrap-ui-config
```

`config/bootstrap-ui.php`
```php
return [
    'toast' => [
        /*
         * Wo sollen Toasts standardmäßig erscheinen?
         * Optionen: top-end, top-start, top-center, bottom-end, bottom-start, bottom-center
         */
        'position' => 'top-end',
        
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
        'auto_dismiss' => 0,
        
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
```

---

## ❤️ Credits

Entwickelt mit Laravel, Livewire und Bootstrap 5.
