<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Project\Infrastructure\Models\Project;
use App\Modules\Task\Infrastructure\Models\Task;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::factory()->count(3)->create()->each(function ($user) {
            Project::factory()->count(2)->create([ 'owner_id' => $user->id ])
                ->each(function ($project) use ($user) {
                    Task::factory()->count(3)->create([
                        'project_id' => $project->id,
                        'creator_id' => $user->id,
                        'assignee_id' => $user->id,
                    ]);
                });
        });
    }
}
