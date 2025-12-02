<?php

namespace Nexus633\BootstrapUi\Services;

use Livewire\Livewire;
use Nexus633\BootstrapUi\Services\Trait\InteractsWithNotifications;

class ToastService
{
    use InteractsWithNotifications;
    protected function fire(string $variant, string $message, ?string $title = null): void
    {
        $payload = [
            'id' => uniqid('toast_', true),
            'variant' => $variant,
            'message' => $message,
            'title' => $title,
            'timestamp' => now()->format('H:i'),
        ];

        // CHECK: Sind wir in Livewire?
        if (class_exists(Livewire::class) && app('livewire')->current()) {
            // JA -> NUR Event feuern! Keine Session!
            app('livewire')->current()->dispatch('bs-toast-message', toast: $payload);
        } else {
            // NEIN (z.B. Controller Redirect) -> Ab in die Session
            $sessionToasts = session()->get('bs-toasts', []);
            $sessionToasts[] = $payload;
            session()->flash('bs-toasts', $sessionToasts);
        }
    }
}
