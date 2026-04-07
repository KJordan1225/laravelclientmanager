<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'in_progress', 'completed']);

        return [
            'client_id' => Client::factory(),
            'title' => fake()->sentence(5),
            'description' => fake()->optional()->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'status' => $status,
            'due_date' => fake()->optional()->dateTimeBetween('now', '+60 days'),
            'assigned_to' => fake()->optional()->name(),
            'completed_at' => $status === 'completed' ? now() : null,
        ];
    }
}
