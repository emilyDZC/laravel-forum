<?php

namespace Database\Factories;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Will only create User from factory if needed
            'title' => str(fake()->sentence)->beforeLast('.')->title(), // str helper removes full stop that sentence creates
            'body' => Collection::times(4, fn () => fake()->realText(1250))->join(PHP_EOL . PHP_EOL),
        ];
    }
}
