<?php

namespace Nexus633\BootstrapUi\Trait;

use Livewire\WithPagination;

/**
 * Trait WithBootstrapUiPagination
 *
 * Dieser Trait erweitert die Standard-Livewire-Paginierung um spezifische
 * Konfigurationsmöglichkeiten für das Bootstrap UI Package.
 * Er ermöglicht die Steuerung von Icons, Zusammenfassungen und Rand-Links
 * direkt aus der Livewire-Komponente heraus.
 *
 * @package Nexus633\BootstrapUi\Trait
 */
trait WithBootstrapUiPagination
{
    use WithPagination;

    /**
     * Setzt das Paginierungs-Theme für Livewire fest.
     * Dies stellt sicher, dass Livewire nicht auf Tailwind zurückfällt.
     *
     * @var string
     */
    protected string $paginationTheme = 'bootstrap-ui';

    /**
     * Bestimmt, ob Navigationspfeile (Icons) statt Text links angezeigt werden.
     * true = Icons (< >), false = Text (Zurück / Weiter).
     *
     * @var bool
     */
    public bool $usePaginationIcons;

    /**
     * Bestimmt, ob der Info-Text ("Zeige 1 bis 10 von 50") angezeigt wird.
     *
     * @var bool
     */
    public bool $displayPaginationSummary;

    /**
     * Bestimmt, ob Buttons für die allererste und allerletzte Seite
     * angezeigt werden sollen.
     *
     * @var bool
     */
    public bool $displayFirstAndLastPage;

    /**
     * Aktiviert die Anzeige von Icons (Pfeilen) in der Navigation.
     *
     * @return void
     */
    public function enablePaginationIcons(): void
    {
        $this->usePaginationIcons = true;
    }

    /**
     * Deaktiviert die Anzeige von Icons und nutzt stattdessen Text.
     *
     * @return void
     */
    public function disablePaginationIcons(): void
    {
        $this->usePaginationIcons = false;
    }

    /**
     * Aktiviert die Anzeige des Zusammenfassungs-Textes (Summary).
     *
     * @return void
     */
    public function showPaginationSummary(): void
    {
        $this->displayPaginationSummary = true;
    }

    /**
     * Versteckt den Zusammenfassungs-Text.
     *
     * @return void
     */
    public function hidePaginationSummary(): void
    {
        $this->displayPaginationSummary = false;
    }

    /**
     * Aktiviert die Links zur ersten und letzten Seite.
     *
     * @return void
     */
    public function enableFirstAndLastPage(): void
    {
        $this->displayFirstAndLastPage = true;
    }

    /**
     * Versteckt die Links zur ersten und letzten Seite.
     *
     * @return void
     */
    public function disableFirstAndLastPage(): void
    {
        $this->displayFirstAndLastPage = false;
    }

    /**
     * Gibt den Pfad zur benutzerdefinierten Blade-View zurück.
     * Diese Methode überschreibt die Standard-Methode von Livewire.
     *
     * @return string
     */
    public function paginationView(): string
    {
        return 'bs::components.pagination';
    }
}
