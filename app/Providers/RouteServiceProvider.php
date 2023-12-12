<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->configureApiRoutes();
            $this->configureWebRoutes();
        });

    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
            ? Limit::perMinute(60)->by($request->user()->id)
            : Limit::perMinute(60)->by($request->ip());
        });
    }

    protected function configureApiRoutes()
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(function () {
                $this->loadRoutesFrom(base_path('routes/api.php'));

                // Additional API route groups
                $this->configureApiRouteGroups(['Teacher/api','Student/api']);
            });
    }

    protected function configureApiRouteGroups(array $groups)

    {
        foreach ($groups as $group) {
            Route::middleware('api')
                ->prefix("{$group}")
                ->group(function () use ($group) {
                    $this->loadRoutesFrom(base_path("routes/{$group}.php"));
                });
        }
    }

    protected function configureWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(function () {
                $this->loadRoutesFrom(base_path('routes/web.php'));
            });
    }


    
}

