<?php

use App\Http\Controllers\Api\OAuthUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| OAuth2 Routes (Laravel Passport)
|--------------------------------------------------------------------------
|
| Route KHUSUS untuk aplikasi web internal "plant" yang memakai OAuth2
| Authorization Code Grant. File ini sengaja dipisah dari routes/api.php
| supaya route JWT milik aplikasi mobile tidak tersentuh sama sekali.
|
| Endpoint di bawah dilindungi guard `passport` (bukan guard `api`/JWT),
| jadi token OAuth2 dan token JWT tidak saling bercampur.
|
*/

Route::middleware(['auth:passport', 'scope:profile:read'])->group(function () {
    Route::get('/oauth/user', [OAuthUserController::class, 'show'])->name('oauth.user');
});
