<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $manager;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        // Create users
        $this->manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->manager->assignRole('manager');

        $this->user = User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->user->assignRole('user');
    }

    /**
     * Test that login returns JWT token with user details.
     */
    public function test_login_returns_token_with_user_details_and_permissions()
    {
        // Test manager login
        $response = $this->postJson('/api/auth/login', [
            'email' => 'manager@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'refresh_expires_in',
            'user' => [
                'id',
                'name',
                'email',
                'roles',
                'permissions',
            ],
            'roles',
            'permissions',
        ]);

        // Verify user details
        $response->assertJsonFragment([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
        ]);

        // Verify roles
        $responseData = $response->json();
        $this->assertContains('manager', $responseData['roles']);

        // Test regular user login
        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'refresh_expires_in',
            'user' => [
                'id',
                'name',
                'email',
                'roles',
                'permissions',
            ],
            'roles',
            'permissions',
        ]);

        // Verify user details
        $response->assertJsonFragment([
            'name' => 'Regular User',
            'email' => 'user@test.com',
        ]);

        // Verify roles
        $responseData = $response->json();
        $this->assertContains('user', $responseData['roles']);
    }

    /**
     * Test that invalid credentials return proper error.
     */
    public function test_login_with_invalid_credentials_returns_error()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'manager@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment(['error' => 'Invalid credentials']);
    }

    /**
     * Test that authenticated user endpoint works.
     */
    public function test_me_endpoint_returns_user_details()
    {
        // First login to get a token
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'manager@test.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('access_token');

        // Now test the me endpoint
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/me');

        $response->assertStatus(200);
        
        // Check the actual structure we're getting
        $responseData = $response->json();
        // print_r($responseData); // Debug line
        
        // The UserResource should return these keys
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('email', $responseData);
        $this->assertArrayHasKey('roles', $responseData);
        $this->assertArrayHasKey('permissions', $responseData);

        $response->assertJsonFragment([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
        ]);
    }
}