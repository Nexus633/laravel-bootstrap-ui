<?php

namespace Nexus633\BootstrapUi\Services;

use Illuminate\Support\Str;

class TreeViewService
{
    protected array $extensionMap = [];
    protected array $folderIcons = [];
    protected string $defaultIcon;

    public function __construct()
    {
        $this->loadConfig();
    }

    protected function loadConfig(): void
    {
        $config = config('bootstrap-ui.tree_view', []);

        $this->folderIcons = $config['folder'] ?? ['closed' => 'folder-fill', 'open' => 'folder2-open'];
        $this->defaultIcon = $config['default_file_icon'] ?? 'file-earmark';

        // Wir drehen das Mapping um: Aus ['icon' => ['jpg', 'png']] wird ['jpg' => 'icon', 'png' => 'icon']
        // Das macht den späteren Zugriff extrem schnell.
        $rawMap = $config['extension_map'] ?? [];
        foreach ($rawMap as $icon => $extensions) {
            foreach ((array)$extensions as $ext) {
                $this->extensionMap[strtolower($ext)] = $icon;
            }
        }
    }

    /**
     * Ermittelt das Icon basierend auf Name und Typ.
     */
    public function getIcon(string $name, bool $isFolder = false, bool $isOpen = false): string
    {
        // 1. Ordner Logik
        if ($isFolder) {
            return $isOpen
                ? ($this->folderIcons['open'] ?? 'folder2-open')
                : ($this->folderIcons['closed'] ?? 'folder-fill');
        }

        // 2. Datei Logik
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        return $this->extensionMap[$extension] ?? $this->defaultIcon;
    }
}
