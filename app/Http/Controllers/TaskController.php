<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function indexTask()
    {
        $tasks = Task::where('user_id', auth()->id())->get();
        return view('index', compact('tasks'));
    }

    public function index(Request $request)
    {
        $query = Task::where('user_id', Auth::id());

        // 🔍 Search by title
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 🎯 Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 📄 Paginate with 5 tasks per page
        $tasks = $query->paginate(5);

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task added successfully!',
            'task' => $task,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'in:pending,completed',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully!',
            'task' => $task,
        ]);
    }

    public function toggleStatus($id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $task->status = $task->status === 'pending' ? 'completed' : 'pending';
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task status updated!',
            'task' => $task,
        ]);
    }

    public function destroy($id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully!']);
    }
}
