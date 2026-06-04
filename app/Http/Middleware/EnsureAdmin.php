<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Acceso solo para administradores.'], 403);
            }

            return redirect()->route('home')->with('error', 'Acceso solo para administradores.');
        }

        return $next($request);
    }
}
