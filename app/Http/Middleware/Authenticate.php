<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Simpan current URL sebagai intended destination sebelum redirect ke login
        // Ini memungkinkan redirect kembali ke OAuth atau intent URL setelah login
        session()->put('url.intended', $request->getRequestUri());
        
        return route('login');
    }
}
