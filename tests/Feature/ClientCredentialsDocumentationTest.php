<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ClientCredentialsDocumentationTest extends TestCase
{
    public function test_guest_diarahkan_ke_login(): void
    {
        $this->get('/docs/client-credentials')
            ->assertRedirect('/login');

        $this->get('/docs/client-credentials/openapi.json')
            ->assertRedirect('/login');
    }

    public function test_user_login_dapat_membuka_dokumentasi(): void
    {
        $this->actingAs($this->user())
            ->get('/docs/client-credentials')
            ->assertOk()
            ->assertSee('OAuth2 untuk komunikasi antar-aplikasi')
            ->assertSee('/oauth/token')
            ->assertSee('plant:read');
    }

    public function test_openapi_hanya_mendokumentasikan_endpoint_client_credentials(): void
    {
        $response = $this->actingAs($this->user())
            ->getJson('/docs/client-credentials/openapi.json')
            ->assertOk()
            ->assertJsonPath('openapi', '3.0.3')
            ->assertJsonPath('components.securitySchemes.clientCredentials.flows.clientCredentials.scopes.plant:read', 'Membaca data dari server Plant.')
            ->assertJsonPath('paths./oauth/token.post.requestBody.content.application/x-www-form-urlencoded.schema.properties.grant_type.example', 'client_credentials');

        $this->assertSame(
            ['/oauth/token', '/api/oauth/client/ping'],
            array_keys($response->json('paths')),
        );
    }

    private function user(): User
    {
        $user = new User;
        $user->id = 1;
        $user->email = 'user@example.test';

        return $user;
    }
}
