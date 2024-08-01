<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    // Endpoint público para listar las tareas con id y nombre
    public function index()
    {
        $tasks = Task::select('id', 'title')->get();
       // dd($tasks);
        return response()->json($tasks);
    }

    // Endpoint privado para obtener las tareas de un usuario
    public function getTasksByUser($id)
    {
        if (Auth::id() != $id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $tasks = Task::where('user_id', $id)->get();
        return response()->json($tasks, 200);
    }

    // Endpoint privado para actualizar una tarea
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (Auth::user()->cannot('update', $task)) {
            return response()->json(['error' => 'Unauthorized to update this task'], 403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'priority' => 'required|in:baja,media,alta',
            'completed' => 'required',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $task->update([
            'title' => $request->title,
            'priority' => $request->priority,
            'completed' => $request->completed,
        ]);

        $task->tags()->sync($request->tags);

        return response()->json(['message' => 'Task updated successfully'], 200);
    }

    // Endpoint privado para eliminar una tarea
    public function destroy($id)
    {
        $task = Task::find($id);

        if (Auth::user()->cannot('delete', $task)) {
            return response()->json(['error' => 'Unauthorized to delete this task'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
