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
            'is_approved' => false,
            'user_id' => User::all()->random()->id,
        ];
    }

    /**
     * approved
     *
     * @return static
     */
    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => true,
            ];
        });
    }
}
