<?php

namespace App\Providers;

use App\Models\OAuthClient;
use App\OAuth\ScopeRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\ScopeRepository as PassportScopeRepository;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PassportScopeRepository::class, ScopeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') == 'production') {
            \URL::forceScheme('https');
        }

        $this->configurePassport();
    }

    /**
     * Konfigurasi OAuth2 server (Laravel Passport) untuk aplikasi internal "plant".
     *
     * PENTING: bagian ini hanya menambah konfigurasi OAuth2. Guard `api` (JWT)
     * dan seluruh route API yang sudah dipakai aplikasi mobile tidak diubah.
     */
    protected function configurePassport(): void
    {
        Passport::useClientModel(OAuthClient::class);

        // Password grant dan implicit grant tidak digunakan.
        Passport::$passwordGrantEnabled = false;
        Passport::$implicitGrantEnabled = false;

        // Scope yang boleh diminta oleh client OAuth2.
        Passport::tokensCan([
            'profile:read' => 'Membaca data profil dasar pengguna (id, nama, email, jabatan, status aktif)',
            'plant:read' => 'Membaca data dari server Plant',
            'plant:edit' => 'Mengubah data di server Plant',
        ]);

        Passport::setDefaultScope([]);

        // Masa berlaku token, diatur lewat .env agar bisa berbeda per environment.
        Passport::tokensExpireIn(
            now()->addMinutes((int) env('PASSPORT_TOKENS_EXPIRE_IN', 60))
        );
        Passport::refreshTokensExpireIn(
            now()->addDays((int) env('PASSPORT_REFRESH_TOKENS_EXPIRE_IN', 14))
        );

        // Halaman persetujuan (consent) milik HRM, menggantikan view bawaan Passport.
        Passport::authorizationView('oauth.authorize');
    }
}
