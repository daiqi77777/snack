<?php

namespace AppProviders;


use Illuminate\Support\ServiceProvider;
use App\Console\Commands\InstallCommand;
use Illuminate\Routing\Route;

class SnackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->commands([
                InstallCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../../config/snack.php' => config_path('snack.php'),
            ], 'config');
        }

        $this->registerRouter();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function registerMigrations()
    {
        $migrationsPath = __DIR__ . '/../../database/migrations/';

        $items = [
            '2022_07_25_081609_admin_users.php',
            '2022_07_25_081532_roles.php',
            '2022_07_25_081545_permissions.php',
            '2022_07_25_081618_menus.php',
            '2022_07_25_081624_permission_groups.php'
        ];

        $paths = [];
        foreach ($items as $key => $name) {
            $paths[$migrationsPath . $name] = database_path('migrations') . "/" . $this->formatTimestamp($key + 1) . '_' . $name;
        }

        $this->publishes($paths, 'migrations');
    }

    /**
     * @param $addition
     * @return false|string
     */
    private function formatTimestamp($addition)
    {
        return date('Y_m_d_His', time() + $addition);
    }

    /**
     * 注册路由
     *
     * @author moell
     */
    private function registerRouter()
    {
        if (strpos($this->app->version(), 'Lumen') === false && !$this->app->routesAreCached()) {
            app('router')->middleware('api')->group(__DIR__ . '/../routes.php');
        } else {
            require __DIR__ . '/../routes.php';
        }
    }
}