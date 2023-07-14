<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    use CommonTrait;

    public function index(Request $request): TaskCollection
    {
        $itemsPerPage = request('per_page') ?? 20;
        $search = request('search');
        $tasks = Task::query();
        $tasks->where('created_by',auth()->id())->orWhere('assign_to',auth()->id());
        if ($search) {
            $tasks->whereLike(['title','description'], $search);
        }
        return new TaskCollection($tasks->orderBy('id','DESC')->paginate($itemsPerPage));
    }

    public function store(TaskStoreRequest $request): TaskResource
    {
        $data = $request->validated();
        $data += $this->storeMetadata($request);
        $task = Task::create($data);

        return new TaskResource($task);
    }

    public function show(Request $request, Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function update(TaskUpdateRequest $request, Task $task): TaskResource
    {
        $data = $request->validated();
        $data += $this->updateMetadata($request);
        $task->update($data);
        return new TaskResource($task);
    }

    public function destroy(Request $request, Task $task): Response
    {
        $task->delete();

        return response()->noContent();
    }

    public function statusChange(Request $request, Task $task, $status)
    {
        $task->update(['status'=>$status]);
        return response()->json(['status'=>'success','message'=>'Status change successfully'],200);
    }
}
