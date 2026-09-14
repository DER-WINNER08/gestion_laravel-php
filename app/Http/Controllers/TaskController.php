<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Database\QueryException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();

        return response()->json($tasks, 200);
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
    public function store(Request $request)
    {
        try {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'nom' => "required|string|max:255",
            "prix" => "required|numeric|min:0",
            'description' => 'nullable|string',
            "category_id" => 'nullable|exists:categories,id',
            'completed' => 'boolean',
        ]);

        Task::create($validatedData);
        
        return response()->json(['message' => "tache créée avec succès"], 201);
    } catch (QueryException $e) {
        return response()->json([
                'success' => false,
                'message' => "Erreur d'intégrité des données : La catégorie spécifiée n'existe pas ou la requête SQL a échoué.",
            ], 400);

    } catch (Exception $e) {
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
    public function show($id)
    {
       try {
            // ERREUR ANTICIPÉE N°1 : La tâche recherchée n'existe pas
            $task = Task::with('category')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $task
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "Ressource introuvable : La tâche avec l'ID {$id} n'existe pas."
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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
    public function update(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);

            if (empty($request->all())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune donnée n\'a été fournie pour la mise à jour.'
                ], 400);
            }
            $task->update($request->all());

            $validatedData = $request->validate([
                'title' => 'sometimes|string|max:255',
                'nom' => "sometimes|string|max:255",
                "prix" => "sometimes|numeric|min:0",
                'description' => 'sometimes|string',
                'completed' => 'sometimes|boolean',
            ]);
            $task->update($validatedData);

            return response()->json([
                'success'=> true,
                'message' => "Tache mise à jour avec succès",
                "task" => $task ], 200);
    }
    catch (ModelNotFoundException $e) {
        return response()->json([
                    'success' => false,
                    'message' => "Impossible de modifier : La tâche avec l'ID {$id} est introuvable."
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
    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'message' => "Tâche {$id} supprimée avec succès"
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de supprimer : La tâche {$id} n'existe pas ou a déjà été supprimée."
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression en base de données.',
            ], 500);
        }
    }
}
