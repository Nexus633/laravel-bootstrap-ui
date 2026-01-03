<?php

namespace Nexus633\BootstrapUi\Facades;

use Illuminate\Support\Facades\Facade;
use Nexus633\BootstrapUi\Services\CommandPaletteService;

/**
 * @method static self register(string $group, array $items)
 * @method static self registerMany(array $groupedCommands)
 * @method static self addCommand(string $group, array $item)
 * @method static array all()
 * * @see CommandPaletteService
 */
class CommandPalette extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bs-command-palette';
    }
}
