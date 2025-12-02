<?php

namespace Nexus633\BootstrapUi\Services;

class ThemeService
{
    // Setzt den Dark Mode in der Session
    public function darkMode(): void
    {
        session()->put('bs-theme', 'dark');
    }

    // Setzt den Light Mode in der Session
    public function lightMode(): void
    {
        session()->put('bs-theme', 'light');
    }

    // Setzt Auto Mode (System)
    public function autoMode(): void
    {
        session()->forget('bs-theme');
    }

    // Gibt den aktuellen Modus zurück (für Blade Checks)
    public function current(): string
    {
        return session('bs-theme', 'auto');
    }
}
