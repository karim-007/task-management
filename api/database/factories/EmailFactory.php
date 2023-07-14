<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Email;
use App\Models\Task;
use App\Models\User;

class EmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Email::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'subject' => $this->faker->regexify('[A-Za-z0-9]{191}'),
            'email' => $this->faker->safeEmail,
            'message' => $this->faker->text,
            'user_id' => User::factory(),
            'task_id' => Task::factory(),
            'try' => $this->faker->word,
            'is_sent' => $this->faker->word,
            'ip' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'browser' => $this->faker->text,
        ];
    }
}
