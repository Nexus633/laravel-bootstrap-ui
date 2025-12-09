<?php

namespace Nexus633\BootstrapUi\Services;

use Livewire\Livewire;

class ModalService
{
    /**
     * Öffnet ein Modal anhand seiner ID (via Browser Event)
     */
    public function show(string $id): void
    {
        $this->fire('bs-modal-show', $id);
    }

    /**
     * Schließt ein Modal
     */
    public function hide(string $id): void
    {
        $this->fire('bs-modal-hide', $id);
    }

    protected function fire(string $event, string $id): void
    {
        if (class_exists(Livewire::class) && app('livewire')->current()) {
            app('livewire')->current()->dispatch($event, id: $id);
        }
    }
}
