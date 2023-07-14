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
    /**
     * @OA\Get(
     *   path="/api/task",
     *   summary="get all tasks",
     *   tags={"tasks"},
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     **   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * *   @OA\Response(
     *          response=500,
     *          description="not found"
     *      )
     *)
     **/
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

    /**
     * @OA\Post(
     *   path="/api/task",
     *   tags={"Task"},
     *   summary="task store",
     *
     *     @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="assing_to",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *      *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
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

    /**
     * @OA\Put(
     *   path="/api/task/{task}",
     *   tags={"Task"},
     *   summary="task store",
     *  @OA\Parameter(
     *      name="task",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="title",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="assing_to",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *      *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
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
