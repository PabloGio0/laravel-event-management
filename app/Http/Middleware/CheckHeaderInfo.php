<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHeaderInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/servicios/busqueda') || $request->isMethod('get')) {
            return $next($request);
        }

        if (! $request->hasHeader('X-APP-KEY') || $request->header("X-APP-KEY") !== config("app.api_key")) {
            return response()->json(["message" => "acceso denegado"], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
