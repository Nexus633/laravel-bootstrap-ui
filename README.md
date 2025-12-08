# 🚀 `nexus633/laravel-bootstrap-ui`

**Einfache, moderne Blade-Komponenten für Laravel und Livewire, basierend auf Bootstrap 5.x.**

Dieses Paket bietet eine Sammlung vorkonfigurierter Blade-Komponenten (z.B. `<x-bs::button>`, `<x-bs::select>`), die gängige UI-Elemente wie Formulare, Grid-Layouts, Toasts und Theme-Umschaltung vereinfachen und Livewire-Ready sind.

-----

## 1\. Installation

Installiere das Paket über Composer:

```bash
  composer require nexus633/laravel-bootstrap-ui
```

### **Assets veröffentlichen (Optional, empfohlen für Produktion)**

Für eine optimale Performance sollten die Assets einmalig in den `public`-Ordner veröffentlicht werden:

```bash
  php artisan vendor:publish --tag=bootstrap-ui-assets
```

-----

## 2\. Setup (Layout-Einbindung)

Um die Styles, Skripte und die globale Logik (Theme-Toggle, Benachrichtigungen) zu laden, integriere die Komponenten in dein Haupt-Layout (`app.blade.php`).

```html
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-bs::head /> 
</head>
<body>

    {{-- Globale Container für Benachrichtigungen --}}
    <x-bs::flash />
    <x-bs::toasts />

    {{ $slot }}

    {{-- Lädt JS, Alpine und kann optional Charts laden --}}
    <x-bs::scripts /> 
</body>
</html>
```

-----

## 3\. Kurzübersicht & Beispiele

Hier sind einige Beispiele, wie die Komponenten verwendet werden:

### **Formulare & Livewire**

Einfache Validierung und Bindung durch das `name`-Attribut:

```html
<x-bs::select
    name="status"
    label="Status wählen"
    :options="['A' => 'Aktiv', 'I' => 'Inaktiv']"
    wire:model="userStatus"
/>

<x-bs::password
    name="password"
    label="Passwort"
    icon="bi-key"
/>
```

### **Grid & Layout**

Einfache responsive Spalten und Gutter-Steuerung:

```html
<x-bs::container>
    <x-bs::row gx="4">
        <x-bs::row.col md="6" lg="4">
            {{-- Inhalt 1 --}}
        </x-bs::row.col>
        <x-bs::row.col md="6" lg="8">
            {{-- Inhalt 2 --}}
        </x-bs::row.col>
    </x-bs::row>
</x-bs::container>
```

### **Benachrichtigungen (Toasts & Alerts)**

Aufruf aus Controllern oder Livewire (die Komponente `<x-bs::toasts>` muss im Layout vorhanden sein):

```php
use Nexus633\BootstrapUi\Facades\Toast;
use Nexus633\BootstrapUi\Facades\Flash;

// Temporäres Pop-up
Toast::success('Die Aktion war erfolgreich!');

// Persistenter Alert (oft nach Redirects)
Flash::warning('Bitte prüfen Sie Ihre Eingaben.');
```

-----

## 4\. Vollständige Dokumentation (Wiki)

Alle Komponenten, Eigenschaften (`@props`), Konfigurationsoptionen und detaillierte Anwendungsbeispiele (inkl. Livewire/Alpine-Optimierungen) findest du in unserem **Wiki**:

[**👉 ZUR VOLLSTÄNDIGEN DOKUMENTATION UND ALLEN BEISPIELEN**](https://github.com/Nexus633/laravel-bootstrap-ui/wiki)
