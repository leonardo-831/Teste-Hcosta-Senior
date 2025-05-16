<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Task\Infrastructure\Models\Task;
use App\Modules\Project\Infrastructure\Models\Project;
use App\Modules\Auth\Infrastructure\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['a_fazer', 'em_progresso', 'concluido']),
            'project_id' => Project::factory(),
            'creator_id' => User::factory(),
            'assignee_id' => User::factory(),
        ];
    }
}
