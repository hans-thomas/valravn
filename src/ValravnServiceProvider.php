<?php

namespace Hans\Valravn;

use Hans\Valravn\Commands\Controller;
use Hans\Valravn\Commands\Controllers;
use Hans\Valravn\Commands\Entity;
use Hans\Valravn\Commands\Exception;
use Hans\Valravn\Commands\Migration;
use Hans\Valravn\Commands\Model;
use Hans\Valravn\Commands\Pivot;
use Hans\Valravn\Commands\Policy;
use Hans\Valravn\Commands\Relation as RelationCommand;
use Hans\Valravn\Commands\Repository;
use Hans\Valravn\Commands\Requests;
use Hans\Valravn\Commands\Resources;
use Hans\Valravn\Commands\Service;
use Hans\Valravn\Exceptions\Package\PublishedVersionOutDatedException;
use Hans\Valravn\Services\Caching\CachingService;
use Hans\Valravn\Services\Filtering\FilteringService;
use Hans\Valravn\Services\Routing\RoutingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Throwable;

class ValravnServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('v-routing-service', RoutingService::class);
        $this->app->bind('v-caching-service', CachingService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @throws Throwable
     *
     * @return void
     */
    public function boot()
    {
        $configFile = __DIR__.'/../config/config.php';
        $config = require $configFile;
        if ($publishedConfigVersion = config('valravn.config_version', false)) {
            throw_if(
                version_compare($config['config_version'], $publishedConfigVersion, '>'),
                new PublishedVersionOutDatedException()
            );
        }

        $this->mergeConfigFrom($configFile, 'valravn');

        $this->registerMacros();
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->registerPublishes();
            $this->registerMigrations();
        }
        if (env('REGISTER_ROUTES', true)) {
            $this->registerRoutes();
        }
    }

    /**
     * Register created commands.
     *
     * @return void
     */
    private function registerCommands(): void
    {
        $this->commands([
            InstallCommand::class,
            Entity::class,
            Controller::class,
            Controllers::class,
            Exception::class,
            Migration::class,
            Model::class,
            Policy::class,
            Repository::class,
            Requests::class,
            Resources::class,
            Service::class,
            RelationCommand::class,
            Pivot::class,
        ]);
    }

    /**
     * Register publishable files.
     *
     * @return void
     */
    private function registerPublishes(): void
    {
        $this->publishes(
            [
                __DIR__.'/../config/config.php' => config_path('valravn.php'),
            ],
            'valravn-config'
        );
        $this->publishes(
            [
                __DIR__.'/../src/stubs/RepositoryServiceProvider.stub' => app_path('Providers/RepositoryServiceProvider.php'),
            ],
            'valravn-provider'
        );
    }

    /**
     * Register common macros.
     *
     * @return void
     */
    private function registerMacros(): void
    {
        if (env('ENABLE_DB_LOG', false)) {
            DB::listen(static function (QueryExecuted $query) {
                $bindings = implode(',', $query->bindings);
                Log::info(
                    $query->sql,
                    ["binding: [ $bindings ] execute time: $query->time"]
                );
            });
        }

        if (!Builder::hasGlobalMacro('applyFilters')) {
            Builder::macro('applyFilters', function (array $options = []) {
                /** @var Builder $this */
                return app(FilteringService::class)->apply($this, $options);
            });
        }

        if (!Relation::hasMacro('applyFilters')) {
            Relation::macro('applyFilters', function (array $options = []) {
                /** @var \Illuminate\Contracts\Database\Eloquent\Builder $this */
                return app(FilteringService::class)->apply($this, $options);
            });
        }

        if (!Builder::hasGlobalMacro('whereLike')) {
            Builder::macro('whereLike', function ($column, $value = null, $boolean = 'and') {
                /** @var Builder $this */
                $this->where($column, 'LIKE', "%{$value}%", $boolean);
            });
        }

        if (!Builder::hasGlobalMacro('orWhereLike')) {
            Builder::macro('orWhereLike', function ($column, $value = null, $boolean = 'and') {
                /** @var Builder $this */
                $this->orWhere($column, 'LIKE', "%{$value}%", $boolean);
            });
        }

        if (!Application::hasMacro('runningInDev')) {
            Application::macro('runningInDev', static function () {
                if (env('APP_ENV', 'local') != 'production') {
                    return true;
                }

                return false;
            });
        }
    }

    /**
     * Register created migrations files by migration command in sub folders.
     *
     * @return void
     */
    private function registerMigrations(): void
    {
        $directories = [];
        foreach (valravn_config('migrations') as $migrationPath) {
            $directories = array_merge(
                $directories,
                array_filter(
                    scandir($migrationPath),
                    static fn ($item) => !in_array($item, ['.', '..'])
                )
            );
        }
        $paths = array_map(
            static fn ($item) => database_path("migrations/$item"),
            $directories
        );

        $this->loadMigrationsFrom($paths);
    }

    /**
     * Automatically register routes defined in routes directory.
     *
     * @return void
     */
    private function registerRoutes(): void
    {
        $path = base_path('routes/');
        $fs = new Filesystem();

        foreach ($fs->allFiles($path) as $file) {
            if (!in_array($file->getBasename(), ['console.php', 'web.php', 'api.php'])) {
                $name = substr($file->getBasename(), 0, strpos($file->getBasename(), '.'));

                Route::prefix("api/$name")
                     ->name("$name.")
                     ->middleware('api')
                     ->group($file->getRealPath());
            }
        }

        Route::getRoutes()->refreshNameLookups();
        Route::getRoutes()->refreshActionLookups();
    }
}
