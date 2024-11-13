<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {

        $user_roles = Auth::guard('web')->user()->user_roles ?? '';
        // return response()->json($user_roles, 200);
        if (in_array($user_roles, $role)) {
            return $next($request);
        }
        if (Auth::guard('web')->check()) {
            return abort(403);
        }
        return redirect()->route('login')
            ->withErrors([
            'email' => 'Silahkan login untuk mengakses halaman',
        ])->onlyInput('email');
    }
}
