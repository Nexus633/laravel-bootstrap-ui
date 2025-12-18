<?php

namespace Nexus633\BootstrapUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getIcon(string $name, bool $isFolder = false, bool $isOpen = false)
 */
class TreeView extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bs-tree-view';
    }
}
