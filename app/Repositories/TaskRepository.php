<?php

namespace App\Repositories;

use App\models\Task;
use App\Models\User;

class TaskRepository
{
    public function getAllForUser(User $user, int $perPage = 2, ?string $status = null, ?int $categoryId = null, ?string $search = null)
    {
        $query = Task::query();

        if ($user->role !=='admin'){
            $query->where("user_id", $user->id);
        }

        if ($status !== null){
            $query->where("status", $status);
        }

        if ($categoryId !== null){
            $query->where("category_id", $categoryId);
        }

        if ($search !== null){
            $query->where(function ($q) use ($search){
                $q->where("title", "like", "%{$search}%")->orWhere("description", "like", "%%{$search}");
            });
        }

        return $query->with('user')->latest()->paginate($perPage);
    }

    public function CreateForUser(User $user, array $data): Task
    {
        return $user->tasks()->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}