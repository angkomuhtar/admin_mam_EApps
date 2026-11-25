<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\Concerns\InteractsWithSeededSchema;
use Tests\TestCase;

/**
 * Force logout semua device: token JWT lama harus ditolak setelah
 * token_version user dinaikkan.
 */
class ForceLogoutTest extends TestCase
{
    use InteractsWithSeededSchema;

    private function makeUser(string $username): User
    {
        return User::create([
            'username' => $username,
            'email' => $username.'@perusahaan.co.id',
            'password' => 'rahasia-kuat-123',
            'user_roles' => 'users',
            'status' => 'Y',
            'phone_id' => 'DEVICE-'.$username,
        ]);
    }

    private function forgetGuard(): void
    {
        // Simulasi request baru: singleton JWT menyimpan token request sebelumnya.
        auth()->forgetGuards();
        app()->forgetInstance('tymon.jwt');
        app()->forgetInstance('tymon.jwt.auth');
    }

    public function test_token_masih_valid_sebelum_force_logout(): void
    {
        $user = $this->makeUser('fl_valid');
        $token = auth('api')->login($user->fresh());

        $this->getJson('/api/v1/me', ['Authorization' => 'Bearer '.$token])
            ->assertStatus(200);
    }

    public function test_token_ditolak_setelah_force_logout(): void
    {
        $user = $this->makeUser('fl_revoked');
        $token = auth('api')->login($user->fresh());

        User::forceLogout([$user->id]);
        $this->forgetGuard();

        $this->getJson('/api/v1/me', ['Authorization' => 'Bearer '.$token])
            ->assertStatus(401);

        $fresh = $user->fresh();
        $this->assertNull($fresh->phone_id);
        $this->assertSame(1, (int) $fresh->token_version);
    }

    public function test_refresh_token_lama_ditolak_setelah_force_logout(): void
    {
        $user = $this->makeUser('fl_refresh');
        $token = auth('api')->login($user->fresh());

        User::forceLogout([$user->id]);
        $this->forgetGuard();

        $this->postJson('/api/v1/auth/refresh', [], ['Authorization' => 'Bearer '.$token])
            ->assertStatus(401);
    }

    public function test_refresh_normal_tetap_jalan_dan_tv_terbawa(): void
    {
        $user = $this->makeUser('fl_refresh_ok');
        User::forceLogout([$user->id]); // tv = 1
        $token = auth('api')->login($user->fresh());
        $this->forgetGuard();

        $res = $this->postJson('/api/v1/auth/refresh', [], ['Authorization' => 'Bearer '.$token])
            ->assertStatus(200);

        $newToken = $res->json('authorisation.token');
        $this->forgetGuard();

        $this->getJson('/api/v1/me', ['Authorization' => 'Bearer '.$newToken])
            ->assertStatus(200);
    }

    public function test_login_ulang_setelah_force_logout_berhasil(): void
    {
        $user = $this->makeUser('fl_relogin');
        User::forceLogout([$user->id]);
        $this->forgetGuard();

        $token = auth('api')->login($user->fresh());

        $this->getJson('/api/v1/me', ['Authorization' => 'Bearer '.$token])
            ->assertStatus(200);
    }

    public function test_force_logout_semua_hanya_user_aktif(): void
    {
        $aktif = $this->makeUser('fl_aktif');
        $nonaktif = $this->makeUser('fl_nonaktif');
        $nonaktif->update(['status' => 'N']);

        User::forceLogout();

        $this->assertSame(1, (int) $aktif->fresh()->token_version);
        $this->assertNull($aktif->fresh()->phone_id);
        $this->assertSame(0, (int) $nonaktif->fresh()->token_version);
        $this->assertSame('DEVICE-fl_nonaktif', $nonaktif->fresh()->phone_id);
    }

    public function test_force_logout_sebagian_tidak_mempengaruhi_user_lain(): void
    {
        $a = $this->makeUser('fl_a');
        $b = $this->makeUser('fl_b');
        $tokenB = auth('api')->login($b->fresh());

        User::forceLogout([$a->id]);
        $this->forgetGuard();

        $this->getJson('/api/v1/me', ['Authorization' => 'Bearer '.$tokenB])
            ->assertStatus(200);
        $this->assertSame('DEVICE-fl_b', $b->fresh()->phone_id);
    }
}
