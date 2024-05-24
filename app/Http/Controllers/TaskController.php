<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Contract\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class TaskController extends Controller
{
    public function __construct(
       private TaskRepositoryInterface $repository
    )
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->repository->getAllTasks()
        ]);
    }

    public function getUnassignedTasks(): JsonResponse
    {
        $tasks = Task::whereNull('user_id')->get();
        return response()->json(['data' => $tasks ], Response::HTTP_CREATED);
    }

    public function store(Request $request): JsonResponse
    {
        // todo
        $attributes = $request->only([
            'title',
            'task',
            'deadline',
        ]);

       return response()->json([ 'data' =>  $this->repository->createTask($attributes)], Response::HTTP_CREATED);
    }

    public function getUserTasks(User $user): JsonResponse
    {
        $tasks = Task::where('user_id', $user->id)->get();
        return response()->json(['data' => $tasks ], Response::HTTP_CREATED);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'data' => $task
        ]);
    }

    public function update(Task $task, Request $request): JsonResponse
    {
        $attributes = $request->only([
            'status',
            'user_id',
            // 'deadline',
            // 'title',
            // 'task',
        ]);

        return response()->json([
            'data' => $this->repository->updateTask($task, $attributes)
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {

        $this->repository->deleteTask($task);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
