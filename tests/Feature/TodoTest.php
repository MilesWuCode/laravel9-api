<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Todo;

class TodoTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        $user = User::inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->get('/api/todo?page=1&limit=2&sort=id_desc');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'total',
            'per_page',
            'last_page',
            'data',
            'current_page',
        ]);
    }

    public function testGet()
    {
        $todo = Todo::inRandomOrder()->first();

        Sanctum::actingAs(
            $todo->user,
            ['*']
        );

        $response = $this->get('/api/todo/' . $todo->id);

        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $user = User::inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->post('/api/todo/', [
            'content' => $this->faker->text(rand(5, 20)),
        ]);

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $user = User::inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $todo = $user->todos()->inRandomOrder()->first();

        $response = $this->post('/api/todo/'.$todo->id, [
            'content' => $this->faker->text(rand(5, 20)),
            'active' => !$todo->active,
            '_method' => 'PUT',
        ]);

        $response->assertStatus(200);
    }

    public function testDelete()
    {
        $user = User::inRandomOrder()->first();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $todo = $user->todos()->inRandomOrder()->first();

        $response = $this->post('/api/todo/'.$todo->id, [
            '_method' => 'DELETE',
        ]);

        $response->assertStatus(200);
    }
}
