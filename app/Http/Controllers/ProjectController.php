<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;

class ProjectController extends Controller
{
    // Show projects and their progress
    public function index()
{
    $user = auth()->user(); // Get the authenticated user

    // Get projects linked to the teams the user is part of
    $teams = $user->teams; // Fetch teams where the user is a member

    // Get projects only for those teams
    $projects = Project::whereIn('team_id', $teams->pluck('id'))->with('tasks')->get();

    // Calculate progress for each project
    foreach ($projects as $project) {
        $project->progress = $this->calculateProgress($project);
    }

    return view('projects.index', compact('projects', 'teams'));
}


    // Function to calculate project progress based on task status
    private function calculateProgress($project)
    {
        $totalTasks = $project->tasks->count();
        if ($totalTasks === 0) return 0;

        $completedTasks = $project->tasks->where('status', 'Done')->count();
        return round(($completedTasks / $totalTasks) * 100, 2);
    }

    // Show project details
    public function show(Project $project)
    {
        
        $project->load(['tasks.files']);
        $project->progress = $this->calculateProgress($project);

        return view('projects.show', compact('project'));
    }

    // Store a new project
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'priority' => 'required|string'
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function destroy(Project $project)
{
    // Ensure the user has permission to delete the project
    if (!auth()->user()->teams->contains($project->team_id)) {
        return redirect()->route('projects.index')->with('error', 'Unauthorized action.');
    }

    $project->delete();

    return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
}

}
