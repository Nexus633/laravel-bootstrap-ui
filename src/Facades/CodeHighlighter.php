<?php

namespace Nexus633\BootstrapUi\Facades;

use Illuminate\Support\Facades\Facade;
use Nexus633\BootstrapUi\Services\CodeHighlighterService;

/**
 * @method static array process(string $content, string $language = 'blade')
 * * @see CodeHighlighterService
 */
class CodeHighlighter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bs-code-highlighter';
    }
}
