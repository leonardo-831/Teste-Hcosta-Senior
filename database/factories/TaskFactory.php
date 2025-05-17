<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Task\Infrastructure\Models\Task;
use App\Modules\Project\Infrastructure\Models\Project;
use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Task\Infrastructure\Models\Status;

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
        $statusIds = Status::pluck('id')->toArray();

        if (empty($statusIds)) {
            $statusIds = [
                Status::create(['name' => 'A Fazer', 'color' => '#ff0000'])->id,
                Status::create(['name' => 'Em Progresso', 'color' => '#ffff00'])->id,
                Status::create(['name' => 'Concluído', 'color' => '#00ff00'])->id,
            ];
        }

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status_id' => $this->faker->randomElement($statusIds),
            'project_id' => Project::factory(),
            'assignee_id' => User::factory(),
        ];
    }
}
