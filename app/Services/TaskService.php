<?php

namespace App\Services;

use App\Exceptions\CategoryNotFoundException;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\User;
use App\Exceptions\UnauthorizedTaskException;
use App\Exceptions\TaskCannotBeDeletedException;
use App\Models\category;

class TaskService
{
    public function __construct(protected TaskRepository $task_repository)
    {}

    public function getUserTasks(User $user, int $perPage = 2, ?string $status = null, ?int $categoryId = null)
    {
        return $this->task_repository->getAllForUser($user, $perPage, $status, $categoryId);
    }

    public function createTask(User $user, array $data): Task
    {
        $category = category::find($data["category_id"]);

        if (!$category)
            {
                throw new CategoryNotFoundException(
                    "la categorie entrée n'esxiste pas"
                );
            }
        return DB::transaction(function () use ($user, $data){
        return $this->task_repository->CreateForUser($user, $data);
        });
    }

    public function updateTask(Task $task, array $data): Task
    {
        if ($task->user_id !== auth()->id) 
            {
                throw new UnauthorizedTaskException(
                    "vous n'etes pas authoriser a modifier cette tache");
            }
        return DB::transaction(function () use ($task, $data){
        return $this->task_repository->update($task, $data);
        });
    }

    public function deleteTask(Task $task): bool
    {   
        if ($task->status === "completed"){
            throw new TaskCannotBeDeletedException("impossible de supprimer une tache terminer");
        }
        return DB::transaction(function () use ($task){
        return $this->task_repository->delete($task);
        });
    }

    
}