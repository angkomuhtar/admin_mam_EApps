<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (config('app.env') == 'production') {
            # code...
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
        // Hanya "authorization code grant" (+ refresh token) yang diizinkan.
        // Password grant & implicit grant dimatikan karena tidak dipakai plant
        // dan keduanya melemahkan keamanan (password client-side, token di URL).
        Passport::$passwordGrantEnabled = false;
        Passport::$implicitGrantEnabled = false;

        // Scope yang boleh diminta oleh client OAuth2.
        Passport::tokensCan([
            'profile:read' => 'Membaca data profil dasar pengguna (id, nama, email, jabatan, status aktif)',
        ]);

        Passport::setDefaultScope(['profile:read']);

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
