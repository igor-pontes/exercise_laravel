<?php

namespace App\Contract;

use App\Models\User;
use App\Models\Task;

interface TaskRepositoryInterface
{
    public function getAllTasks();
    public function getTaskById(Task $task);
    public function getUnassignedTasks();
    public function getUserTasksById(User $user);
    public function deleteTask(Task $task);
    public function createTask(array $attributes);
    public function updateTask(Task $task, array $attributes);
}

