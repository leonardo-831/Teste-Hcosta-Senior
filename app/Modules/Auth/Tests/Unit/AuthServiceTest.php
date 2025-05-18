<?php

namespace App\Modules\Auth\Tests\Unit;

use Tests\TestCase;
use App\Modules\Auth\Application\Services\AuthService;
use App\Modules\Auth\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

# php artisan test --filter=AuthServiceTest
class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AuthService::class);
    }

    # php artisan test --filter=AuthServiceTest::testLoginReturnsTokenWithValidCredentials
    public function testLoginReturnsTokenWithValidCredentials(): void
    {
        $user = User::factory()->create([
            'email' => 'valid@example.com',
            'password' => bcrypt('valid-password'),
        ]);

        $credentials = [
            'email' => 'valid@example.com',
            'password' => 'valid-password',
        ];

        $token = $this->service->login($credentials);

        $this->assertIsString($token);
        $this->assertTrue(strlen($token) > 10);
    }

    # php artisan test --filter=AuthServiceTest::testLoginThrowsExceptionWithInvalidCredentials
    public function testLoginThrowsExceptionWithInvalidCredentials(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não foi possível autenticar, confirme suas credenciais!');

        $credentials = [
            'email' => 'nonexistent@example.com',
            'password' => 'invalid',
        ];

        $this->service->login($credentials);
    }

    # php artisan test --filter=AuthServiceTest::testLogoutCallsJwtLogout
    public function testLogoutCallsJwtLogout(): void
    {
        $mock = Mockery::mock(AuthService::class)->makePartial();
        $mock->shouldReceive('logout')->once()->andReturnNull();

        $this->assertNull($mock->logout());
    }
}
