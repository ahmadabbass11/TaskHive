<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
    // Store a new task for a project
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:To-Do,In Progress,Done',
            'due_date' => 'nullable|date',
            'priority' => 'required|string'
        ]);

        Task::create([
            'project_id' => $projectId,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'assignee' => $request->assignee_id,
            'due_date' => $request->due_date,
            'priority' => $request->priority
        ]);

        return redirect()->route('projects.show', $projectId)->with('success', 'Task added successfully.');
    }
    public function destroy(Task $task)
{
    $projectId = $task->project_id; // Get project ID before deleting

    // Delete the task
    $task->delete();

    return redirect()->route('projects.show', $projectId)->with('success', 'Task deleted successfully.');
}

}
