<?php

namespace Nexus633\BootstrapUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void darkMode()
 * @method static void lightMode()
 * @method static void autoMode()
 * @method static string current()
 */
class ThemeSwitch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bs-theme';
    }
}
