<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {

    $rolesPermitidos = explode(',', $role);
    $userRol = $request->header('X-USER-ROLE');
        if (! $userRol || !in_array($userRol, $rolesPermitidos)){
            return response()->json([
                'message' => 'no tienes el rol para acceder a esta ruta'
            ], Response::HTTP_FORBIDDEN);
        }

        Log::channel('single')->info("Rol recibido", ['rol' => $request->header('X-USER-ROLE'),
        'aceptado' => $role]);
        return $next($request);
    }
}
