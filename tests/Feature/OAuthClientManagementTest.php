<?php

namespace Tests\Feature;

use Laravel\Passport\Passport;
use Tests\Concerns\InteractsWithSeededSchema;
use Tests\TestCase;

class OAuthClientManagementTest extends TestCase
{
    use InteractsWithSeededSchema;

    public function test_oauth_client_routes_exist()
    {
        // Just verify routes are registered
        $routes = collect(\Route::getRoutes())->map->getName();
        
        $this->assertContains('masters.oauth.client', $routes);
        $this->assertContains('masters.oauth.client.store', $routes);
        $this->assertContains('masters.oauth.client.destroy', $routes);
    }

    public function test_oauth_client_controller_instantiates()
    {
        // Controller should instantiate without errors
        $controller = app(\App\Http\Controllers\Admin\OAuthClientController::class);
        $this->assertNotNull($controller);
    }

    public function test_can_create_oauth_client_in_database()
    {
        // Just verify that Passport client creation works
        $client = Passport::client()->create([
            'user_id' => null,
            'name' => 'Test OAuth Client',
            'secret' => 'test-secret-abc123',
            'redirect' => 'https://example.com/callback',
            'personal_access_client' => false,
            'password_client' => false,
            'revoked' => false,
        ]);

        $this->assertNotNull($client->id);
        $this->assertEquals('Test OAuth Client', $client->name);
        $this->assertFalse($client->revoked);
    }

    public function test_can_revoke_oauth_client_in_database()
    {
        // Create, then revoke
        $client = Passport::client()->create([
            'user_id' => null,
            'name' => 'Test Revoke Client',
            'secret' => 'test-secret-123',
            'redirect' => 'https://example.com/callback',
            'personal_access_client' => false,
            'password_client' => false,
            'revoked' => false,
        ]);

        $clientId = $client->id;
        
        $client->update(['revoked' => true]);
        
        $client->refresh();
        $this->assertTrue($client->revoked);
    }
}
