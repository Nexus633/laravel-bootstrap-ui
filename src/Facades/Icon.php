<?php

namespace Nexus633\BootstrapUi\Facades;

use Illuminate\Support\Facades\Facade;
class Icon extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bs-icon-service'; // Der Key im Service Container
    }
}
