<?php

namespace Nexus633\BootstrapUi\Services\Trait;

trait InteractsWithNotifications
{
    public function success(string $message, string $title = 'Erfolg'): void
    {
        $this->fire('success', $message, $title);
    }

    public function error(string $message, string $title = 'Fehler'): void
    {
        $this->fire('danger', $message, $title);
    }

    public function danger(string $message, string $title = 'Fehler'): void
    {
        $this->fire('danger', $message, $title);
    }

    public function info(string $message, string $title = 'Info'): void
    {
        $this->fire('info', $message, $title);
    }

    public function warning(string $message, string $title = 'Warnung'): void
    {
        $this->fire('warning', $message, $title);
    }
}
