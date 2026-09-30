<?php

namespace Tests\Feature;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\Concerns\InteractsWithSeededSchema;
use Tests\TestCase;

/**
 * Regresi: memastikan login JWT lama untuk aplikasi mobile tetap berfungsi
 * setelah Passport dipasang. Guard `api` tidak boleh tergantikan.
 */
class JwtLoginRegressionTest extends TestCase
{
    use InteractsWithSeededSchema;

    public function test_login_jwt_lama_tetap_menghasilkan_token(): void
    {
        $user = User::create([
            'username' => 'mobileuser',
            'email' => 'mobile@perusahaan.co.id',
            'password' => 'rahasia-kuat-123',
            'user_roles' => 'users',
            'status' => 'Y',
        ]);

        $token = auth('api')->attempt([
            'email' => 'mobile@perusahaan.co.id',
            'password' => 'rahasia-kuat-123',
        ]);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);

        $decoded = JWTAuth::setToken($token);
        $authenticated = $decoded->authenticate();

        $this->assertTrue((bool) $authenticated);
        // Claim `sub` pada JWT disimpan sebagai string.
        $this->assertEquals($user->id, $decoded->getPayload()->get('sub'));
    }

    public function test_endpoint_api_lama_masih_terproteksi_jwt(): void
    {
        $this->postJson('/api/v1/auth/login', [
            'email' => 'tidak-ada@perusahaan.co.id',
            'password' => 'apapun-123',
            'phone_id' => 'TEST-DEVICE-1',
        ])->assertStatus(401);
    }

    public function test_token_jwt_tidak_bisa_dipakai_untuk_endpoint_oauth(): void
    {
        $user = User::create([
            'username' => 'mobileuser2',
            'email' => 'mobile2@perusahaan.co.id',
            'password' => 'rahasia-kuat-123',
            'user_roles' => 'users',
            'status' => 'Y',
        ]);

        $token = auth('api')->attempt([
            'email' => 'mobile2@perusahaan.co.id',
            'password' => 'rahasia-kuat-123',
        ]);

        $this->getJson('/api/oauth/user', ['Authorization' => 'Bearer '.$token])
            ->assertStatus(401);
    }
}
