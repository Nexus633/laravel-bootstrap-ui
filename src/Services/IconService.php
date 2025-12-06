<?php

namespace Nexus633\BootstrapUi\Services;

use Illuminate\Support\Str;

class IconService
{
    /**
     * Entfernt gängige Prefixes, um den "rohen" Namen zu erhalten.
     * Aus 'bi-person', 'fa-person' oder 'icon:person' wird 'person'.
     */
    public function normalize(?string $iconName): ?string
    {
        if (empty($iconName)) {
            return null;
        }

        // 1. "icon:" Syntax entfernen
        if (Str::startsWith($iconName, 'icon:')) {
            $iconName = Str::substr($iconName, 5);
        }

        // 2. Bekannte Prefixes entfernen (damit wir flexibel bleiben)
        // Wir entfernen sowohl Bootstrap 'bi-' als auch FontAwesome 'fa-'
        $prefixes = ['bi-', 'fa-', 'fas-', 'far-', 'fab-'];

        foreach ($prefixes as $prefix) {
            if (Str::startsWith($iconName, $prefix)) {
                return Str::substr($iconName, strlen($prefix));
            }
        }

        return $iconName;
    }

    /**
     * Baut die fertige CSS-Klasse basierend auf der Config.
     */
    public function toClass(?string $iconName): string
    {
        // Wenn jemand explizit Leerzeichen nutzt (z.B. "fab fa-twitter"),
        // gehen wir davon aus, er weiß, was er tut, und geben es direkt zurück.
        if (Str::contains($iconName, ' ')) {
            return $iconName;
        }

        $clean = $this->normalize($iconName);

        if (!$clean) {
            return '';
        }

        // Config laden (mit Fallback auf Bootstrap Icons)
        $base = config('bootstrap-ui.icons.base', 'bi');
        $prefix = config('bootstrap-ui.icons.prefix', 'bi-');

        // Baut z.B. "bi bi-user" oder "fas fa-user"
        return "{$base} {$prefix}{$clean}";
    }
}
