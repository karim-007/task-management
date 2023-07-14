<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Models\User;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'deadline' => $this->faker->dateTime(),
            'assign_at' => $this->faker->dateTime(),
            'assign_by' => User::factory(),
            'assign_to' => User::factory(),
            'status' => $this->faker->numberBetween(-8, 8),
            'is_active' => $this->faker->boolean,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
            'ip' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'browser' => $this->faker->text,
        ];
    }
}
