<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function user_can_see_all_tasks()
    {
        Task::factory()->create();

        $response = $this->get('/task');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function task_can_be_created()
    {
        $count = 5 ;

        Task::factory()->count($count)->create();

        $this->assertDatabaseCount('tasks', $count);
    }


}
