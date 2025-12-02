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
        Blade::directive('bootstrapStyles', function () {
            return "<?php
                if (file_exists(public_path('vendor/nexus633/bootstrap-ui/css/bootstrap.min.css'))) {
                    echo '<link rel=\"stylesheet\" href=\"' . asset('vendor/nexus633/bootstrap-ui/css/bootstrap.min.css') . '\">';
                } else {
                    echo '<link rel=\"stylesheet\" href=\"' . route('bs-ui.asset', ['path' => 'css/bootstrap.min.css']) . '\">';
                }
                
                if (file_exists(public_path('vendor/nexus633/bootstrap-ui/css/bootstrap-ui.css'))) {
                    echo '<link rel=\"stylesheet\" href=\"' . asset('vendor/nexus633/bootstrap-ui/css/bootstrap-ui.css') . '\">';
                } else {
                    echo '<link rel=\"stylesheet\" href=\"' . route('bs-ui.asset', ['path' => 'css/bootstrap-ui.css']) . '\">';
                }
            ?>";
        });

        Blade::directive('bootstrapScripts', function () {
            return "<?php
                if (file_exists(public_path('vendor/nexus633/bootstrap-ui/js/bootstrap.bundle.min.js'))) {
                    echo '<script src=\"' . asset('vendor/nexus633/bootstrap-ui/js/bootstrap.bundle.min.js') . '\"></script>';
                } else {
                    echo '<script src=\"' . route('bs-ui.asset', ['path' => 'js/bootstrap.bundle.min.js']) . '\"></script>';
                }
            ?>";
        });

        Blade::directive('bootstrapIcons', function () {
            return "<?php
            if (file_exists(public_path('vendor/nexus633/bootstrap-ui/css/bootstrap-icons.min.css'))) {
                echo '<link rel=\"stylesheet\" href=\"' . asset('vendor/nexus633/bootstrap-ui/css/bootstrap-icons.min.css') . '\">';
            } else {
                echo '<link rel=\"stylesheet\" href=\"' . route('bs-ui.asset', ['path' => 'css/bootstrap-icons.min.css']) . '\">';
            }
        ?>";
        });

        Blade::directive('bootstrapThemeScript', function () {
            return <<<'PHP'
            <?php
                // Wir holen den Wert aus der Session (gesetzt via ThemeSwitch Facade)
                $serverTheme = session('bs-theme');
            ?>
            <script>
                (function() {
                    // PHP Wert an JS übergeben
                    const serverSessionTheme = "<?php echo $serverTheme; ?>";
                    
                    const getStoredTheme = () => localStorage.getItem('theme');
                    const setStoredTheme = theme => localStorage.setItem('theme', theme);
            
                    const getPreferredTheme = () => {
                        // 1. Priorität: Server Session (wenn ThemeSwitch::darkMode() benutzt wurde)
                        if (serverSessionTheme) {
                            return serverSessionTheme;
                        }
                        
                        // 2. Priorität: LocalStorage
                        const storedTheme = getStoredTheme();
                        if (storedTheme) {
                            return storedTheme;
                        }
            
                        // 3. Priorität: System Einstellung
                        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    };
            
                    const setTheme = theme => {
                        if (theme === 'auto') {
                            document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
                        } else {
                            document.documentElement.setAttribute('data-bs-theme', theme);
                        }
                    };
            
                    // Initiale Ausführung
                    const activeTheme = getPreferredTheme();
                    setTheme(activeTheme);
                    
                    // Falls der Server was befohlen hat, speichern wir das auch lokal für die Zukunft
                    if (serverSessionTheme) {
                        setStoredTheme(serverSessionTheme);
                    }
            
                    // Globaler Toggle Helper
                    window.toggleBootstrapTheme = function() {
                        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                        
                        setStoredTheme(newTheme);
                        setTheme(newTheme);
                        return newTheme;
                    }
                })();
            </script>
            PHP;
        });
    }
}
