<?php

namespace Nexus633\BootstrapUi\Facades;

use Nexus633\BootstrapUi\Support\BootstrapUi as BootstrapUiImpl;

class BootstrapUi
{
    public static function make(?string $name = null): BootstrapUiImpl
    {
        return new BootstrapUiImpl($name);
    }
}
