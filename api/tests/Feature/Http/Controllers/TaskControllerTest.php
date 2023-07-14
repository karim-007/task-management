<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TaskController
 */
class TaskControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $tasks = Task::factory()->count(3)->create();

        $response = $this->get(route('task.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'store',
            \App\Http\Requests\TaskStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $deadline = $this->faker->dateTime();
        $assign_at = $this->faker->dateTime();
        $status = $this->faker->numberBetween(-8, 8);
        $is_active = $this->faker->boolean;

        $response = $this->post(route('task.store'), [
            'deadline' => $deadline,
            'assign_at' => $assign_at,
            'status' => $status,
            'is_active' => $is_active,
        ]);

        $tasks = Task::query()
            ->where('deadline', $deadline)
            ->where('assign_at', $assign_at)
            ->where('status', $status)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $tasks);
        $task = $tasks->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('task.show', $task));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'update',
            \App\Http\Requests\TaskUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $task = Task::factory()->create();
        $deadline = $this->faker->dateTime();
        $assign_at = $this->faker->dateTime();
        $status = $this->faker->numberBetween(-8, 8);
        $is_active = $this->faker->boolean;

        $response = $this->put(route('task.update', $task), [
            'deadline' => $deadline,
            'assign_at' => $assign_at,
            'status' => $status,
            'is_active' => $is_active,
        ]);

        $task->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($deadline, $task->deadline);
        $this->assertEquals($assign_at, $task->assign_at);
        $this->assertEquals($status, $task->status);
        $this->assertEquals($is_active, $task->is_active);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('task.destroy', $task));

        $response->assertNoContent();

        $this->assertSoftDeleted($task);
    }
}
