<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and add security-related headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Defend against clickjacking: allow only same-origin framing
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);
        
        // In production, enable a protective CSP that blocks cross-origin script/image beacons
        if (app()->environment('production')) {
            $csp = [
                "default-src 'self'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'self'",
                "object-src 'none'",
                "script-src 'self' https://maps.googleapis.com https://maps.gstatic.com",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net",
                "img-src 'self' data: https://maps.gstatic.com https://maps.googleapis.com https://flagcdn.com",
                "connect-src 'self' https://maps.googleapis.com https://maps.gstatic.com",
                "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net",
            ];
            $response->headers->set('Content-Security-Policy', implode('; ', $csp), false);
        } else {
            // In non-production, allow Vite dev server and required external assets
            $csp = [
                "default-src 'self'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'self'",
                "object-src 'none'",
                "script-src 'self' http://localhost:5173 http://127.0.0.1:5173",
                "style-src 'self' 'unsafe-inline' http://localhost:5173 http://127.0.0.1:5173 https://fonts.googleapis.com https://fonts.bunny.net",
                "img-src 'self' data: https://flagcdn.com",
                "connect-src 'self' http://localhost:5173 http://127.0.0.1:5173 ws://127.0.0.1:5173",
                "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net",
            ];
            $response->headers->set('Content-Security-Policy', implode('; ', $csp), false);
        }

        // Sensible additional hardening (no breaking changes expected)
        $response->headers->set('X-Content-Type-Options', 'nosniff', false);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);

        return $response;
    }
}


