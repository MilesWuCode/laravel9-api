<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'title' => $this->faker->text(rand(5, 200)),
            'body' => $this->faker->paragraph(),
            'status' => PostStatus::Draft->value,
            'publish_at' => $this->faker->date(),
        ];
    }
}
