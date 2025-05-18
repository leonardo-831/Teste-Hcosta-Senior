<?php

namespace App\Modules\Task\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Project\Infrastructure\Models\Project;
use Tymon\JWTAuth\Facades\JWTAuth;

# php artisan test --filter=TaskFeatureTest
class TaskFeatureTest extends TestCase
{
    use RefreshDatabase;

    # php artisan test --filter=TaskFeatureTest::testUserCanCreateTask
    public function testUserCanCreateTask(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson("/api/projects/{$project->id}/tasks", [
            'name' => 'Test Task',
            'description' => 'Details',
            'statusId' => 1,
            'assigneeId' => $user->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Test Task']);
    }

    # php artisan test --filter=TaskFeatureTest::testCreateTaskFailsWithInvalidData
    public function testCreateTaskFailsWithInvalidData(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson("/api/projects/{$project->id}/tasks", [
            'name' => '',
            'statusId' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'statusId']);
    }

    # php artisan test --filter=TaskFeatureTest::testUserCanUpdateTask
    public function testUserCanUpdateTask(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);
        $task = $project->tasks()->create([
            'name' => 'Old Task',
            'description' => '',
            'status_id' => 1,
            'creator_id' => $user->id,
            'assignee_id' => $user->id,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->putJson("/api/tasks/{$task->id}", [
            'name' => 'Updated Task',
            'description' => 'Updated',
            'statusId' => 2,
            'assignee_id' => $user->id
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Updated Task']);
    }
}
