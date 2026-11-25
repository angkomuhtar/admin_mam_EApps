<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ResponseHelper;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $guard = Auth::guard('api');

        if ($guard->check()) {
            // Token yang dicabut lewat force logout punya claim `tv` lebih kecil dari DB.
            if (!$guard->user()->tokenVersionMatches($guard->payload()->get('tv'))) {
                return ResponseHelper::jsonError('Unauthorized', 401);
            }
            return $next($request);
        }
        return ResponseHelper::jsonError('Unauthorized', 401);
    }
}
