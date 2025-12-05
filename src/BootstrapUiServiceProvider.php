<?php

namespace Nexus633\BootstrapUi;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Nexus633\BootstrapUi\Services\FlashService;
use Nexus633\BootstrapUi\Services\ThemeService;
use Nexus633\BootstrapUi\Services\ToastService;

class BootstrapUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Views registrieren
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bs');


        $this->bootPublishes();
        $this->bootRoutes();
        $this->bootBladeDirective();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/bootstrap-ui.php', 'bootstrap-ui'
        );

        $this->registerSingleton();

    }

    private function registerSingleton(): void
    {
        $this->app->singleton('bs-flash', function () {
            return new FlashService();
        });

        $this->app->singleton('bs-theme', function () {
            return new ThemeService();
        });

        $this->app->singleton('bs-toast', function () {
            return new ToastService();
        });
    }

    private function bootPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/nexus633/bootstrap-ui'),
        ], 'bootstrap-ui-assets');

        $this->publishes([
            __DIR__.'/../config/bootstrap-ui.php' => config_path('bootstrap-ui.php'),
        ], 'bootstrap-ui-config'); // Neuer Tag!
    }
    private function bootRoutes(): void
    {
        Route::get('nexus633/bs-ui/assets/{path}', function ($path) {
            $filePath = __DIR__ . '/../resources/assets/' . $path;

            if (! file_exists($filePath)) {
                abort(404);
            }

            // NEU: Erkennung für Fonts
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $mimeType = match($extension) {
                'css'   => 'text/css',
                'js'    => 'application/javascript',
                'woff'  => 'font/woff',
                'woff2' => 'font/woff2',
                'ttf'   => 'font/ttf',
                'svg'   => 'image/svg+xml',
                default => 'text/plain',
            };

            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        })->where('path', '.*')->name('bs-ui.asset');
    }

    private function bootBladeDirective(): void
    {

    }
}
