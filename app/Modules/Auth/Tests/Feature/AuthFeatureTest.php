<?php

namespace App\Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Auth\Infrastructure\Models\User;
use Tests\TestCase;

# php artisan test --filter=AuthFeatureTest
class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    # php artisan test --filter=AuthFeatureTest::testUserCanRegisterSuccessfully
    public function testUserCanRegisterSuccessfully(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    # php artisan test --filter=AuthFeatureTest::testRegistrationFailsWithInvalidData
    public function testRegistrationFailsWithInvalidData(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    # php artisan test --filter=AuthFeatureTest::testUserCanLoginSuccessfully
    public function testUserCanLoginSuccessfully(): void
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('secret123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'secret123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token']);
    }

    # php artisan test --filter=AuthFeatureTest::testLoginFailsWithWrongCredentials
    public function testLoginFailsWithWrongCredentials(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('correct-password')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Não foi possível autenticar, confirme suas credenciais!']);
    }
}
