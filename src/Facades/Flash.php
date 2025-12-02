<?php

namespace Nexus633\BootstrapUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void success(string $message, string $title = null)
 * @method static void error(string $message, string $title = null)
 * @method static void danger(string $message, string $title = null)
 * @method static void warning(string $message, string $title = null)
 * @method static void info(string $message, string $title = null)
 */
class Flash extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bs-flash';
    }
}
