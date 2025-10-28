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
use Illuminate\Session\TokenMismatchException;



return Application::configure(basePath: dirname(__DIR__)) // Tentukan base path aplikasi
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',        // File route web
        commands: __DIR__ . '/../routes/console.php', // File route console/command
        health: '/up',                               // Endpoint health check
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Alias middleware untuk Spatie Permission
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class, // Cek role user
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class, // Cek permission
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class // Role atau permission
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tangani TokenMismatchException (error 419)
        $exceptions->renderable(function (TokenMismatchException $e, Request $request) {
            if (! $request->expectsJson()) {
                return redirect()->route('login')
                    ->with('status', 'Sesi kamu telah berakhir. Silakan login kembali.');
            }

            return response()->json([
                'message' => 'Sesi kamu telah berakhir. Silakan login kembali.'
            ], 419);
        });

        // Tangani UnauthorizedException
        $exceptions->renderable(function (UnauthorizedException $e, Request $request) {

            // Jika request bukan JSON, redirect balik dengan pesan error
            if (! $request->expectsJson()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }

            // Jika request JSON, kembalikan response JSON 403
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        });
    })
    ->create(); // Buat instance aplikasi
