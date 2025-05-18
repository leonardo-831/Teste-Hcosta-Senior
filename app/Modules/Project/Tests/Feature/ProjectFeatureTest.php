<?php

namespace App\Modules\Project\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Project\Infrastructure\Models\Project;
use Tymon\JWTAuth\Facades\JWTAuth;

# php artisan test --filter=ProjectFeatureTest
class ProjectFeatureTest extends TestCase
{
    use RefreshDatabase;

    # php artisan test --filter=ProjectFeatureTest::testAuthenticatedUserCanCreateProject
    public function testAuthenticatedUserCanCreateProject(): void
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/projects', [
            'name' => 'New Project',
            'description' => 'A test project',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', [
            'name' => 'New Project',
            'owner_id' => $user->id
        ]);
    }

    # php artisan test --filter=ProjectFeatureTest::testGuestCannotCreateProject
    public function testGuestCannotCreateProject(): void
    {
        $response = $this->postJson('/api/projects', [
            'name' => 'New Project'
        ]);

        $response->assertStatus(401);
    }

    # php artisan test --filter=ProjectFeatureTest::testProjectCreationFailsWithInvalidData
    public function testProjectCreationFailsWithInvalidData(): void
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/projects', [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    # php artisan test --filter=ProjectFeatureTest::testAuthenticatedUserCanUpdateProject
    public function testAuthenticatedUserCanUpdateProject(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/projects/{$project->id}", [
            'name' => 'Updated Project',
            'description' => 'Updated description'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'name' => 'Updated Project',
            'description' => 'Updated description'
        ]);
    }

    # php artisan test --filter=ProjectFeatureTest::testUserCannotUpdateOtherUsersProject
    public function testUserCannotUpdateOtherUsersProject(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user1->id]);

        $token = JWTAuth::fromUser($user2);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/projects/{$project->id}", [
            'name' => 'Malicious Update'
        ]);

        $response->assertStatus(403);
    }
}
