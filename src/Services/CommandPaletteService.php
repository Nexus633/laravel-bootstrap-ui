<?php

namespace Nexus633\BootstrapUi\Services;

use Illuminate\Support\Str;
use Nexus633\BootstrapUi\Facades\Icon;

class CommandPaletteService
{
    /**
     * Datenstruktur: ['Gruppenname' => [Item1, Item2]]
     * Das ist genau das Format, das unsere Alpine-Schleife erwartet.
     */
    protected array $commands = [];

    /**
     * Registriert eine ganze Gruppe von Befehlen.
     * Beispiel:
     * ->register('Navigation', [ ...items... ])
     */
    public function register(string $group, array $items): self
    {
        if (!isset($this->commands[$group])) {
            $this->commands[$group] = [];
        }

        foreach ($items as $item) {
            $this->addCommandToGroup($group, $item);
        }

        return $this;
    }

    /**
     * Registriert mehrere Gruppen auf einmal.
     * Beispiel:
     * ->registerMany([
     * 'Navigation' => [...],
     * 'System' => [...]
     * ])
     */
    public function registerMany(array $groupedCommands): self
    {
        foreach ($groupedCommands as $group => $items) {
            $this->register($group, $items);
        }

        return $this;
    }

    /**
     * Fügt einen einzelnen Befehl hinzu.
     * Nützlich für bedingtes Hinzufügen.
     */
    public function addCommand(string $group, array $item): self
    {
        // Sicherstellen, dass die Gruppe existiert
        if (!isset($this->commands[$group])) {
            $this->commands[$group] = [];
        }

        $this->addCommandToGroup($group, $item);

        return $this;
    }

    /**
     * Interne Methode zum Normalisieren und Hinzufügen.
     */
    protected function addCommandToGroup(string $group, array $item): void
    {
        // 1. ID Generieren (falls fehlt)
        if (!isset($item['id'])) {
            // Wir nutzen md5 über Label+Action für eine deterministische ID
            $item['id'] = md5(($item['label'] ?? '') . ($item['action'] ?? '') . $group);
        }

        // 2. Icon Normalisieren (Optional, falls du Strings wie 'bi-house' nutzt)
        // Wenn du Icon::toClass() schon im Provider nutzt, ist das hier redundant, aber sicher ist sicher.
        if (isset($item['icon']) && !Str::contains($item['icon'], ' ')) {
            $item['icon'] = Icon::toClass($item['icon']);
        }

        // 3. Defaults setzen
        $item = array_merge([
            'description' => null,
            'keywords' => null,
            'params' => [],
        ], $item);

        $this->commands[$group][] = $item;
    }

    /**
     * Gibt alle Befehle zurück.
     * (Direkt im Format für AlpineJS)
     */
    public function all(): array
    {
        return $this->commands;
    }
}
