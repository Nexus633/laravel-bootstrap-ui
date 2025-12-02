<?php

namespace Nexus633\BootstrapUi\Services;

use Livewire\Livewire;
use Nexus633\BootstrapUi\Services\Trait\InteractsWithNotifications;

class FlashService
{
    use InteractsWithNotifications;
    /**
     * Zentrale Methode
     */
    protected function fire(string $variant, string $message, ?string $title = null): void
    {
        $payload = [
            'id' => uniqid('flash_', true),
            'variant' => $variant,
            'message' => $message,
            'title' => $title,
        ];

        // CHECK: Sind wir in Livewire?
        if (class_exists(Livewire::class) && app('livewire')->current()) {
            // JA -> Event feuern (Payload direkt übergeben)
            app('livewire')->current()->dispatch('bs-flash-message', flash: $payload);
        } else {
            // NEIN -> Session Array nutzen (Stacking möglich machen!)
            $flashes = session()->get('bs-flash', []);
            $flashes[] = $payload;
            session()->flash('bs-flash', $flashes);
        }
    }
}
