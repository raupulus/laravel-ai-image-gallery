<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para inyectar cabeceras HTTP de seguridad en todas las respuestas.
 */
class SecurityHeaders
{
    /**
     * Añade cabeceras de defensa en profundidad, CSP Report-Only con nonce y HSTS bajo HTTPS.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);
        View::share('cspNonce', $nonce);

        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), focus-without-user-activation=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');

        $contentType = (string) $response->headers->get('Content-Type', '');
        if ($contentType === '' || str_contains($contentType, 'text/html')) {
            $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        }

        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' 'report-sample' https://www.googletagmanager.com https://static.cloudflareinsights.com",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: blob: https://www.google-analytics.com https://*.google-analytics.com https://www.googletagmanager.com",
            "font-src 'self' data:",
            "connect-src 'self' https://www.google-analytics.com https://*.google-analytics.com https://*.analytics.google.com https://www.googletagmanager.com https://cloudflareinsights.com",
            'frame-src https://www.youtube-nocookie.com',
            "frame-ancestors 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            'upgrade-insecure-requests',
        ];

        $response->headers->set('Content-Security-Policy-Report-Only', implode('; ', $cspDirectives));

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }
}
