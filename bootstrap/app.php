<?php

use Illuminate\Http\Request;
use App\Http\Middleware\DisableCoopForGoogleAuth;
use Illuminate\Foundation\Application;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {


        $middleware->web(append: [
            DisableCoopForGoogleAuth::class, // <-- Tambahkan ini
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (UnauthorizedException $e, Request $request) {

            if (! $request->expectsJson()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }

            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        });
    })->create();
