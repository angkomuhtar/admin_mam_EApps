<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Tests\Concerns\InteractsWithSeededSchema;
use Tests\TestCase;

class OAuthPlantTest extends TestCase
{
    use InteractsWithSeededSchema;

    protected string $redirectUri = 'https://plant.contoh.internal/auth/callback';

    protected function setUp(): void
    {
        parent::setUp();

        // Konfirmasi guard JWT lama tidak berubah karena Passport.
        $this->assertSame('jwt', config('auth.guards.api.driver'));
        $this->assertSame('passport', config('auth.guards.passport.driver'));
    }

    protected function plantClient(): Client
    {
        $client = new Client();
        $client->name = 'plant';
        $client->secret = 'plant-test-secret';
        $client->provider = 'users';
        $client->redirect = $this->redirectUri;
        $client->personal_access_client = false;
        $client->password_client = false;
        $client->revoked = false;
        $client->save();

        return $client;
    }

    protected function makePosition(string $title): Position
    {
        return Position::create([
            'company_id' => 1,
            'division_id' => 1,
            'position' => $title,
        ]);
    }

    protected function makeEmployee(int $userId, int $positionId): Employee
    {
        return Employee::create([
            'user_id' => $userId,
            'company_id' => 1,
            'project_id' => 1,
            'division_id' => 1,
            'position_id' => $positionId,
            'contract_status' => 'ACTIVE',
            'status' => 'Permanent',
        ]);
    }

    protected function activeUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'username' => 'budi',
            'email' => 'budi@perusahaan.co.id',
            'password' => 'rahasia-kuat-123',
            'user_roles' => 'users',
            'status' => 'Y',
        ], $overrides));
    }

    /**
     * Mengambil authorization code lewat alur GET /oauth/authorize + POST approve.
     * Ini persis alur yang dipakai plant, bukan shortcut internal.
     */
    protected function fetchAuthorizationCode(User $user, Client $client, string $state = 'state-abc'): string
    {
        $query = http_build_query([
            'client_id' => $client->getKey(),
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'profile:read',
            'state' => $state,
        ]);

        $authorize = $this->actingAs($user, 'web')
            ->get('/oauth/authorize?'.$query);

        $authorize->assertOk();
        $authorize->assertSee('plant');

        preg_match('/name="auth_token" value="([^"]+)"/', $authorize->getContent(), $m);
        $this->assertNotEmpty($m, 'auth_token tidak ditemukan di halaman consent');

        $approve = $this->actingAs($user, 'web')
            ->post('/oauth/authorize', [
                'state' => $state,
                'client_id' => $client->getKey(),
                'auth_token' => $m[1],
            ]);

        $approve->assertRedirect();
        $location = $approve->headers->get('Location');

        parse_str(parse_url($location, PHP_URL_QUERY) ?? '', $params);
        $this->assertArrayHasKey('code', $params, 'Redirect tidak membawa authorization code');

        return $params['code'];
    }

    protected function exchangeCodeForToken(Client $client, string $code): array
    {
        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $client->getKey(),
            'client_secret' => 'plant-test-secret',
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ]);

        $response->assertOk();

        return $response->json();
    }

    public function test_authorization_code_flow_berhasil_memberikan_token(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        $code = $this->fetchAuthorizationCode($user, $client);
        $token = $this->exchangeCodeForToken($client, $code);

        $this->assertArrayHasKey('access_token', $token);
        $this->assertArrayHasKey('refresh_token', $token);
        $this->assertArrayHasKey('expires_in', $token);
        $this->assertSame('Bearer', $token['token_type']);

        // Passport 11 tidak selalu mengirim `scope` di response token.
        if (isset($token['scope'])) {
            $this->assertContains('profile:read', explode(' ', $token['scope']));
        }

        // Scope tetap terekam di tabel token.
        $this->assertContains('profile:read', Token::where('user_id', $user->id)->latest('id')->value('scopes'));

        // Kode hanya bisa dipakai sekali.
        $reuse = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $client->getKey(),
            'client_secret' => 'plant-test-secret',
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ]);
        $reuse->assertStatus(400);
        $this->assertStringContainsString('revoked', $reuse->json('hint'));

        $this->assertDatabaseHas('oauth_access_tokens', ['user_id' => $user->id]);
    }

    public function test_redirect_uri_salah_ditolak(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();
        $code = $this->fetchAuthorizationCode($user, $client);

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $client->getKey(),
            'client_secret' => 'plant-test-secret',
            'redirect_uri' => 'https://penyerang.example/callback',
            'code' => $code,
        ]);

        $response->assertStatus(401);
    }

    public function test_token_tanpa_scope_ditolak_untuk_endpoint_user(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        // Token asli dari alur authorization code, lalu scope-nya dihapus dari DB
        // untuk meniru token yang terbit tanpa scope.
        $code = $this->fetchAuthorizationCode($user, $client);
        $token = $this->exchangeCodeForToken($client, $code);

        Token::where('user_id', $user->id)->update(['scopes' => '[]']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token['access_token'])
            ->getJson('/api/oauth/user');

        $response->assertStatus(403);
    }

    public function test_endpoint_user_tanpa_token_ditolak(): void
    {
        $this->getJson('/api/oauth/user')->assertStatus(401);
    }

    public function test_endpoint_user_mengembalikan_data_user_yang_benar(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        $code = $this->fetchAuthorizationCode($user, $client);
        $token = $this->exchangeCodeForToken($client, $code);

        $response = $this->withHeader('Authorization', 'Bearer '.$token['access_token'])
            ->getJson('/api/oauth/user');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'username' => 'budi',
                'email' => 'budi@perusahaan.co.id',
                'is_active' => true,
            ],
        ]);

        $this->assertArrayHasKey('name', $response->json('data'));
        $this->assertArrayHasKey('job_title', $response->json('data'));
        $this->assertArrayHasKey('position_id', $response->json('data'));

        // Tidak boleh membocorkan field sensitif.
        $this->assertArrayNotHasKey('password', $response->json('data'));
        $this->assertArrayNotHasKey('fcm_token', $response->json('data'));
        $this->assertArrayNotHasKey('remember_token', $response->json('data'));
    }

    public function test_endpoint_user_mengembalikan_name_dan_job_title_dari_relasi(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        $position = $this->makePosition('Operator Mesin');
        $this->makeEmployee($user->id, $position->id);
        $user->profile()->create([
            'name' => 'Budi Santoso',
            'card_id' => 'TEST-CARD-1',
            'kk' => 'TEST-KK-1',
            'education' => 1,
            'tmp_lahir' => 'Bandung',
            'tgl_lahir' => '1990-01-01',
            'gender' => 'M',
            'religion' => 1,
            'marriage' => 1,
            'id_addr' => 'TEST',
            'live_addr' => 'TEST',
            'phone' => '081234567890',
        ]);

        Passport::actingAs($user->fresh(), ['profile:read'], 'passport');

        $response = $this->getJson('/api/oauth/user');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'name' => 'Budi Santoso',
                'job_title' => 'Operator Mesin',
                'position_id' => $position->id,
            ],
        ]);
    }

    public function test_user_nonaktif_menghasilkan_is_active_false(): void
    {
        $user = $this->activeUser(['status' => 'N']);

        Passport::actingAs($user, ['profile:read'], 'passport');

        $response = $this->getJson('/api/oauth/user');

        $response->assertOk();
        $response->assertJson(['data' => ['is_active' => false]]);
    }

    public function test_password_grant_dimatikan(): void
    {
        $this->activeUser();

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => '1',
            'client_secret' => 'rahasia',
            'username' => 'budi',
            'password' => 'rahasia-kuat-123',
            'scope' => 'profile:read',
        ]);

        // League OAuth2 menolak grant_type yang tidak terdaftar.
        $response->assertStatus(400);
        $this->assertSame(
            'unsupported_grant_type',
            $response->json('error')
        );
    }

    public function test_implicit_grant_dimatikan(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        $response = $this->actingAs($user, 'web')->get('/oauth/authorize?'.http_build_query([
            'client_id' => $client->getKey(),
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'token',
            'scope' => 'profile:read',
            'state' => 'state-implisit',
        ]));

        // Implicit grant wajib nonaktif di AppServiceProvider.
        $this->assertFalse(Passport::$implicitGrantEnabled, 'Implicit grant tidak boleh aktif.');
        $this->assertFalse(Passport::$passwordGrantEnabled, 'Password grant tidak boleh aktif.');

        // Response type "token" ditolak: server tidak mendukung implicit grant.
        $response->assertStatus(400);
        $response->assertJson(['error' => 'unsupported_grant_type']);
        $this->assertEmpty($response->headers->get('Location'), 'Token tidak boleh bocor lewat URL.');
    }

    public function test_refresh_token_memberi_token_baru(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        $code = $this->fetchAuthorizationCode($user, $client);
        $token = $this->exchangeCodeForToken($client, $code);

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'refresh_token',
            'client_id' => $client->getKey(),
            'client_secret' => 'plant-test-secret',
            'refresh_token' => $token['refresh_token'],
            'scope' => 'profile:read',
        ]);

        $response->assertOk();
        $this->assertArrayHasKey('access_token', $response->json());
    }

    public function test_token_yang_dicabut_ditolak(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        $code = $this->fetchAuthorizationCode($user, $client);
        $token = $this->exchangeCodeForToken($client, $code);

        $this->assertSame(200, $this->withHeader('Authorization', 'Bearer '.$token['access_token'])
            ->getJson('/api/oauth/user')->status());

        // Mencabut token sama dengan menandai token revoked di tabel oauth_access_tokens.
        Token::where('user_id', $user->id)->update(['revoked' => true]);

        // Guard Passport menyimpan user di memori antar-request dalam satu test,
        // jadi harus dibersihkan agar request kedua benar-benar memvalidasi JWT.
        $this->app['auth']->forgetGuards();

        $this->assertSame(401, $this->withHeader('Authorization', 'Bearer '.$token['access_token'])
            ->getJson('/api/oauth/user')->status());
    }

    public function test_route_api_lama_tidak_berubah(): void
    {
        $routes = collect(app('router')->getRoutes()->getRoutes())
            ->map(fn ($r) => $r->methods()[0].' '.$r->uri())
            ->all();

        // Route JWT milik aplikasi mobile harus tetap ada di tempat semula.
        $this->assertContains('POST api/v1/auth/login', $routes);
        $this->assertContains('POST api/v1/auth/register', $routes);
        $this->assertContains('POST api/v1/auth/refresh', $routes);
        $this->assertContains('GET login', $routes);
        $this->assertContains('POST logout', $routes);

        // Endpoint OAuth2 baru ada di prefix yang sama tapi tidak menimpa apa pun.
        $this->assertContains('GET api/oauth/user', $routes);
    }

    public function test_access_token_tidak_tersimpan_mentah_di_database(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        $code = $this->fetchAuthorizationCode($user, $client);
        $token = $this->exchangeCodeForToken($client, $code);

        $stored = Token::where('user_id', $user->id)->latest('id')->first();

        // Passport menyimpan reference token, bukan access token JWT itu sendiri.
        $this->assertNull($stored->token);
        $this->assertStringNotContainsString($token['access_token'], json_encode($stored->toArray()));

        // Baris auth code pun tidak boleh memuat kode itu sendiri.
        $authCode = \Laravel\Passport\AuthCode::where('user_id', $user->id)->latest('id')->first();
        $this->assertStringNotContainsString($code, json_encode($authCode?->toArray() ?? []));
    }

    public function test_scope_tersimpan_di_token(): void
    {
        $user = $this->activeUser();
        $client = $this->plantClient();

        $code = $this->fetchAuthorizationCode($user, $client);
        $this->exchangeCodeForToken($client, $code);

        $stored = Token::where('user_id', $user->id)->latest('id')->first();

        $this->assertContains('profile:read', $stored->scopes);
    }
}
