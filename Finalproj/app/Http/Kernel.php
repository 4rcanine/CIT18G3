<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class, // Usually commented out unless needed
        \App\Http\Middleware\TrustProxies::class, // We just created this - uncomment/add if needed
        \Illuminate\Http\Middleware\HandleCors::class, // For handling CORS headers
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class, // Standard maintenance mode check
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // Checks max post size
        \App\Http\Middleware\TrimStrings::class, // Trims whitespace from input
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // Converts empty strings to null
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class, // Handles cookie encryption
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // Adds cookies from queue
            \Illuminate\Session\Middleware\StartSession::class, // Starts the session
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // Optional: Re-auths session
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Makes validation errors available in views
            \App\Http\Middleware\VerifyCsrfToken::class, // CSRF protection
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Enables route model binding
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // If using Sanctum SPA auth
            'throttle:api', // API rate limiting
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     * Key: alias name. Value: Middleware class or alias.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [ // Renamed from $routeMiddleware in newer Laravel, check your version if this causes issues
        'auth' => \App\Http\Middleware\Authenticate::class, // Default authentication middleware
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Redirects logged-in users from guest pages
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // For email verification
        // Add other aliases if needed by packages (e.g., 'role', 'permission' for Spatie packages)
    ];
}