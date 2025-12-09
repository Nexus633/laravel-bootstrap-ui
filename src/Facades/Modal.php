<?php

namespace Nexus633\BootstrapUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void show(string $id)
 * @method static void hide(string $id)
 */
class Modal extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bs-modal';
    }
}
