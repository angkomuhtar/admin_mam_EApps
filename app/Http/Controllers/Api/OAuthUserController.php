<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OAuthUserResource;
use Illuminate\Http\Request;

class OAuthUserController extends Controller
{
    /**
     * Display the authenticated user for an OAuth2 client.
     *
     * Route dijaga oleh middleware `auth:passport` + `scope:profile:read`,
     * sehingga hanya token OAuth2 dari Authorization Code Grant yang bisa
     * mengaksesnya. Token JWT lama tidak bisa memakai endpoint ini.
     */
    public function show(Request $request)
    {
        $user = $request->user()->load(['profile', 'employee.position']);

        return new OAuthUserResource($user);
    }
}
