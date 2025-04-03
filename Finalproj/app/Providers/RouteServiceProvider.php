<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

protected $namespace = 'App\Http\Controllers'; // Property already existed from your paste

    public function boot(): void
    {
        Log::info('--- RouteServiceProvider Boot method starting ---');

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Log::info('--- Loading API routes ---');
            Route::middleware('api')
                ->prefix('api')
                 // You could add ->namespace($this->namespace . '\Api') here if you create API controllers later
                ->group(base_path('routes/api.php'));

            Log::info('--- Loading WEB routes ---');
            Route::middleware('web')
                ->namespace($this->namespace) // <-- * ADD THIS LINE ***
                ->group(base_path('routes/web.php'));
        });

        Log::info('--- RouteServiceProvider Boot method finished ---');
    }
}