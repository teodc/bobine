<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The models to bind to routes.
     *
     * @var array
     */
    public $bindings = [
        'user' => User::class,
    ];

    /**
     * This namespace is applied to your controller routes.
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        // Register route model bindings
        foreach ($this->bindings as $parameter => $class)
        {
            Route::model($parameter, $class);
        }

        // Register query parameter pattern filters
        Route::pattern('id', '[0-9]+');
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapAuthRoutes();
        $this->mapApiRoutes(1);
    }

    /**
     * @param int $version
     * @return void
     */
    private function mapApiRoutes(int $version = 1): void
    {
        // Map guest routes
        Route::domain(config('app.url'))
             ->namespace("{$this->namespace}\\Api\\V{$version}")
             ->prefix("v{$version}")
             ->middleware(['throttle:60,1', 'json', 'bindings'])
             ->group(base_path("routes/api/v{$version}/guest.php"));

        // Map user routes
        Route::domain(config('app.url'))
             ->namespace("{$this->namespace}\\Api\\V{$version}")
             ->prefix("v{$version}")
             ->middleware(['throttle:60,1', 'json', 'auth:api', 'bindings'])
             ->group(base_path("routes/api/v{$version}/user.php"));
    }

    /**
     * @return void
     */
    private function mapAuthRoutes(): void
    {
        // Map guest routes
        Route::domain(config('app.url'))
             ->namespace("{$this->namespace}\\Api\\Auth")
             ->prefix('auth')
             ->middleware(['throttle:60,1', 'json', 'bindings'])
             ->group(base_path("routes/api/auth/guest.php"));
    }
}
