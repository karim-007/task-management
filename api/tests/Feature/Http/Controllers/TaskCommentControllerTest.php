<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TaskComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TaskCommentController
 */
class TaskCommentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $taskComments = TaskComment::factory()->count(3)->create();

        $response = $this->get(route('task-comment.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskCommentController::class,
            'store',
            \App\Http\Requests\TaskCommentStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $response = $this->post(route('task-comment.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(taskComments, [ /* ... */ ]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $taskComment = TaskComment::factory()->create();

        $response = $this->get(route('task-comment.show', $taskComment));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskCommentController::class,
            'update',
            \App\Http\Requests\TaskCommentUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $taskComment = TaskComment::factory()->create();

        $response = $this->put(route('task-comment.update', $taskComment));

        $taskComment->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $taskComment = TaskComment::factory()->create();

        $response = $this->delete(route('task-comment.destroy', $taskComment));

        $response->assertNoContent();

        $this->assertSoftDeleted($taskComment);
    }
}
