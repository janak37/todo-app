<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'title' => fake()->sentence(4),
        'description' => fake()->optional()->paragraph(),
        'submission_date' => fake()->optional()->dateTimeBetween('now', '+1 month'),
        'is_completed' => false,
        ];
    }
}
