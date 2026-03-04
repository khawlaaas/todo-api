<?php

namespace App\Http\Controllers;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::all();
        return response()->json([
            'message' => 'Tâches récupérées avec succès',
            'data' => $todos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean', // Change this to 'boolean'
            'priority' => 'sometimes|in:low,medium,high'
        ]);

        $todo = Todo::create($request->all());

        return response()->json([
            'message' => 'Tâche créée avec succès',
            'data' => $todo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        return response()->json([
            'message' => 'Tâche récupérée avec succès',
            'data' => $todo
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean|nullable',
            'priority' => 'nullable|in:low,medium,high'
        ]);

        $todo->update($validated);

        return response()->json([
            'message' => 'Tâche mise à jour avec succès',
            'data' => $todo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function complete($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        $todo->update(['completed' => true]);

        return response()->json([
            'message' => 'Tâche marquée comme complétée',
            'data' => $todo
        ], 200);
    }

    public function destroy($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        $todo->delete();

        // Le code 204 signifie "No Content", mais pour inclure un message JSON,
        // on peut aussi utiliser 200. Le TP demande 204/404.
        return response()->json([
            'message' => 'Tâche supprimée avec succès'
        ], 204);
    }
}
