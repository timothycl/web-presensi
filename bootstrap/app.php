<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
         'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
         ]);

         $middleware->appendToGroup('api', [
         EnsureFrontendRequestsAreStateful::class,
         ]);

         $middleware->validateCsrfTokens(except: [
             'admin/logout',
         ]);

         $middleware->web(append: [
             // \App\Http\Middleware\NoCacheResponse::class,
         ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
                return null;
            }
            
            return redirect()->back()->withInput($request->except('_token'));
        });
    })->create();
