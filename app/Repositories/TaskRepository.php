<?php

namespace App\Repositories;

use App\Contract\TaskRepositoryInterface;
use App\Models\User;
use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasks()
    {
        return Task::all();
    }

    public function getTaskById(Task $task)
    {
        return $task;
    }

    public function getUserTasksById(User $user) {
        $tasks = Task::where('use_id', $user)->get();
        return $tasks;
    }

    public function getUnassignedTasks(): JsonResponse
    {
        $tasks = Task::whereNull('user_id')->get();
        return response()->json(['data' => $tasks ], Response::HTTP_CREATED);
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
    }

    public function createTask(array $attributes)
    {
        return Task::create($attributes);
    }

    public function updateTask(Task $task, array $attributes)
    {
        return $task->update($attributes);
    }
}
