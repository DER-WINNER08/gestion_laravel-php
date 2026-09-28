<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedTaskException;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\TaskCannotBeDeletedException;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskFilterRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(protected TaskService $task_service)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index(TaskFilterRequest $request): AnonymousResourceCollection
    {
        $perPage = $request->integer("per_page", 10);

        $status = $request->input("status");

        $categoryId = $request->input("category_id");

        $tasks = $this->task_service->getUserTasks($request->user(), $perPage, $status, $categoryId);

        return TaskResource::collection($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = $this->task_service->createTask($request->user(), $request->validated());
        
            return response()->json([
                'message' => "tache créée avec succès",
                'data'    => new TaskResource($task)
                ], 201);
        }
        catch (CategoryNotFoundException $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
        catch (Exception $e) {
        {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de créer la tâche suite à un problème serveur.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {

            $this->authorize('view', $task);
            return new TaskResource($task->load('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $request, Task $task,): JsonResponse
    {
        try {

            if (empty($request->all())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune donnée n\'a été fournie pour la mise à jour.'
                ], 400);
            }

            $this->authorize('update, $task');
            $updatedTask = $this->task_service->updateTask($task, $request->validated());

            return response()->json([
                'success'=> true,
                'message' => "Tache mise à jour avec succès",
                "data" => new TaskResource($updatedTask) ], 200);
    }

    catch (UnauthorizedTaskException $e){
        return response()->json([
            "success" => false,
            "message" => "vous n'etes pas authorizer a faire cette action"
        ], 403);
    } 
    catch (ModelNotFoundException $e) {
        return response()->json([
                    'success' => false,
                    'message' => "Impossible de modifier : La tâche est introuvable."
                ], 404);

    } catch (QueryException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Échec de la mise à jour due à un conflit de données SQL.',
            ], 400);
            }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            $this->authorize('delete, $task');
            $this->task_service->deleteTask($task);

            return response()->json([
                'message' => "Tâche supprimée avec succès"
            ], 200);
        }
        catch (TaskCannotBeDeletedException $e){
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 400);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de supprimer : La tâche n'existe pas ou a déjà été supprimée."
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression en base de données.',
            ], 500);
        }
    }
}
