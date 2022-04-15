<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'commentable_type' => Post::class,
            'commentable_id' => Post::all()->random()->id,
            'comment' => $this->faker->text(rand(5, 20)),
            'is_approved' => $this->faker->boolean(),
            'user_id' => User::all()->random()->id,
        ];
    }
}
