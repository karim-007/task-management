<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCommentStoreRequest;
use App\Http\Requests\TaskCommentUpdateRequest;
use App\Http\Resources\TaskCommentCollection;
use App\Http\Resources\TaskCommentResource;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskCommentController extends Controller
{
    public function index(Request $request): TaskCommentCollection
    {
        $taskComments = TaskComment::all();

        return new TaskCommentCollection($taskComments);
    }

    public function store(TaskCommentStoreRequest $request): TaskCommentResource
    {
        $taskComment = TaskComment::create($request->validated());

        return new TaskCommentResource($taskComment);
    }

    public function show(Request $request, TaskComment $taskComment): TaskCommentResource
    {
        return new TaskCommentResource($taskComment);
    }

    public function update(TaskCommentUpdateRequest $request, TaskComment $taskComment): TaskCommentResource
    {
        $taskComment->update($request->validated());

        return new TaskCommentResource($taskComment);
    }

    public function destroy(Request $request, TaskComment $taskComment): Response
    {
        $taskComment->delete();

        return response()->noContent();
    }
}
