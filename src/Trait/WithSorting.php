<?php

namespace Nexus633\BootstrapUi\Trait;

trait WithSorting
{
    public string $sortColumn = 'id'; // Standard Spalte
    public string $sortDirection = 'asc'; // Standard Richtung

    public function sortBy(string $column): void
    {
        if ($this->sortColumn === $column) {
            // Wenn gleiche Spalte -> Richtung umkehren
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Neue Spalte -> Standard 'asc'
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }
}
