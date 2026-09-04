<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

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
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'nom' => "required|string|max:255",
            "prix" => "required|numeric|min:0",
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        Task::create($validatedData);
        
        return response()->json(['message' => "tache créée avec succès"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Task::find($id);

        return response()->json($task, 200);
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
        $task = task::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'nom' => "sometimes|string|max:255",
            "prix" => "sometimes|numeric|min:0",
            'description' => 'sometimes|string',
            'completed' => 'sometimes|boolean',
        ]);

        $task->update($validatedData);
        $task->refresh();

        return response()->json([
            'message' => "Tache mise à jour avec succès",
            "task" => $task ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      $task = Task::findOrFail($id);

    $task->delete();

    return response()->json([
        'message' => "Tâche {$id} supprimée avec succès"
    ], 200);
    }
}
