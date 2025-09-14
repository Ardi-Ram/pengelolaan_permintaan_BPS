<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableCoopForGoogleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Menambahkan header Cross-Origin-Opener-Policy
        // 'unsafe-none' memungkinkan pop-up lintas-origin untuk berinteraksi dengan opener-nya.
        // Ini penting untuk otorisasi Google Identity Services.
        $response->headers->set('Cross-Origin-Opener-Policy', 'unsafe-none');

        return $response;
    }
}
