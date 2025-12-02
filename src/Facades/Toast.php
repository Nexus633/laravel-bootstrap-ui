<?php

namespace Nexus633\BootstrapUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void success(string $message, string $title = 'Erfolg')
 * @method static void error(string $message, string $title = 'Fehler')
 * @method static void info(string $message, string $title = 'Info')
 * @method static void warning(string $message, string $title = 'Warnung')
 */
class Toast extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bs-toast';
    }
}
