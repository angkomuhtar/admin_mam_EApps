<?php

use App\Http\Controllers\Api\OAuthMasterController;
use App\Http\Controllers\Api\OAuthUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| OAuth2 Routes (Laravel Passport)
|--------------------------------------------------------------------------
|
| Route KHUSUS untuk aplikasi web internal "plant" dan "mam-mining" yang
| memakai OAuth2 Authorization Code Grant. File ini sengaja dipisah dari
| routes/api.php supaya route JWT milik aplikasi mobile tidak tersentuh.
|
| Endpoint di bawah dilindungi guard `passport` (bukan guard `api`/JWT),
| jadi token OAuth2 dan token JWT tidak saling bercampur.
|
*/

Route::middleware(['auth:passport', 'scope:profile:read'])->group(function () {
    Route::get('/oauth/user', [OAuthUserController::class, 'show'])->name('oauth.user');

    // Data master untuk aplikasi client OAuth2.
    Route::prefix('oauth/master')->controller(OAuthMasterController::class)->group(function () {
        Route::get('/company', 'company')->name('oauth.master.company');
        Route::get('/project/{id}', 'project')->name('oauth.master.project');
        Route::get('/division/{id}', 'division')->name('oauth.master.division');
        Route::get('/position/{id}', 'position')->name('oauth.master.position');
        Route::get('/position_class', 'position_class')->name('oauth.master.position_class');
        Route::get('/site', 'site')->name('oauth.master.site');
    });
});

Route::middleware('client:plant:read')->get('/oauth/client/ping', function () {
    return response()->json([
        'authenticated' => true,
        'service' => config('app.name'),
    ]);
})->name('oauth.client.ping');
