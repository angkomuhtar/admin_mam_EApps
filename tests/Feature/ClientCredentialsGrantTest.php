<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\OAuthClientController;
use App\Models\OAuthClient;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use Tests\Concerns\InteractsWithSeededSchema;
use Tests\TestCase;

class ClientCredentialsGrantTest extends TestCase
{
    use InteractsWithSeededSchema;

    protected function client(string $redirect = '', array $scopes = null): OAuthClient
    {
        $client = new OAuthClient();
        $client->name = $redirect === '' ? 'internal-service' : 'web-client';
        $client->secret = 'internal-test-secret';
        $client->redirect = $redirect;
        $client->personal_access_client = false;
        $client->password_client = false;
        $client->revoked = false;
        $client->scopes = $scopes ?? ($redirect === '' ? ['plant:read'] : ['profile:read']);
        $client->save();

        return $client;
    }

    public function test_service_client_can_request_token_and_access_protected_endpoint(): void
    {
        $client = $this->client();

        $tokenResponse = $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getKey(),
            'client_secret' => 'internal-test-secret',
        ]);

        $tokenResponse
            ->assertOk()
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token'])
            ->assertJsonMissing(['refresh_token']);

        $this->getJson('/api/oauth/client/ping', [
            'Authorization' => 'Bearer '.$tokenResponse->json('access_token'),
        ])->assertOk()->assertJson([
            'authenticated' => true,
            'service' => config('app.name'),
        ]);

        $token = Token::where('client_id', $client->getKey())->latest('id')->firstOrFail();
        $this->assertNull($token->user_id);
        $this->assertSame(['plant:read'], $token->scopes);
    }

    public function test_service_client_cannot_request_unassigned_scope(): void
    {
        $client = $this->client();

        $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getKey(),
            'client_secret' => 'internal-test-secret',
            'scope' => 'profile:read',
        ])->assertStatus(400)->assertJson([
            'error' => 'invalid_scope',
        ]);
    }

    public function test_authorization_code_client_cannot_use_client_credentials_grant(): void
    {
        $client = $this->client('https://internal.example/auth/callback');

        $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getKey(),
            'client_secret' => 'internal-test-secret',
            'scope' => 'plant:read',
        ])->assertStatus(401)->assertJson([
            'error' => 'invalid_client',
        ]);
    }

    public function test_protected_endpoint_rejects_request_without_token(): void
    {
        $this->getJson('/api/oauth/client/ping')->assertUnauthorized();
    }

    public function test_revoking_service_client_also_revokes_issued_tokens(): void
    {
        $client = $this->client();

        $tokenResponse = $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getKey(),
            'client_secret' => 'internal-test-secret',
        ])->assertOk();

        app(OAuthClientController::class)->destroy($client->getKey());

        $this->getJson('/api/oauth/client/ping', [
            'Authorization' => 'Bearer '.$tokenResponse->json('access_token'),
        ])->assertUnauthorized();
    }

    public function test_regenerating_secret_invalidates_old_secret_for_new_tokens(): void
    {
        $client = $this->client();
        $oldSecret = $client->secret;

        app(OAuthClientController::class)->regenerateSecret($client->getKey());

        $client->refresh();
        $this->assertNotSame($oldSecret, $client->secret);
        $this->assertSame($client->secret, session('oauth_client_created.secret'));

        $tokenRequest = [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getKey(),
        ];

        $this->postJson('/oauth/token', $tokenRequest + [
            'client_secret' => $oldSecret,
        ])->assertStatus(401);

        $this->postJson('/oauth/token', $tokenRequest + [
            'client_secret' => $client->secret,
        ])->assertOk();
    }

    public function test_client_can_request_subset_of_assigned_scopes(): void
    {
        $client = $this->client('', ['plant:read', 'plant:edit']);

        $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getKey(),
            'client_secret' => 'internal-test-secret',
            'scope' => 'plant:edit',
        ])->assertOk();

        $token = Token::where('client_id', $client->getKey())->latest('id')->firstOrFail();
        $this->assertSame(['plant:edit'], $token->scopes);
    }

    public function test_updating_client_scopes_revokes_existing_tokens(): void
    {
        $client = $this->client();

        $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getKey(),
            'client_secret' => 'internal-test-secret',
        ])->assertOk();

        app(OAuthClientController::class)->updateScopes(
            Request::create('/', 'PATCH', ['scopes' => ['plant:edit']]),
            $client->getKey()
        );

        $client->refresh();
        $this->assertSame(['plant:edit'], $client->scopes);
        $this->assertTrue(Token::where('client_id', $client->getKey())->latest('id')->firstOrFail()->revoked);

        $this->postJson('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getKey(),
            'client_secret' => 'internal-test-secret',
        ])->assertOk();

        $this->assertSame(
            ['plant:edit'],
            Token::where('client_id', $client->getKey())->where('revoked', false)->firstOrFail()->scopes
        );
    }
}
