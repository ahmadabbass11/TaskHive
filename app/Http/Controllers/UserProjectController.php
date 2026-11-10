<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class UserProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get projects for the teams the user is part of
        $projects = $user->teams()->with('project')->get()->pluck('project')->filter();

        return view('user.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $user = auth()->user();

        // Ensure the user is part of the team for security
        if (!$project->team->users->contains($user)) {
            abort(403, 'Unauthorized action.');
        }

        $milestones = $project->milestones;
        $tasks = $project->tasks()->where('assignee', $user->id)->get();

        return view('user.projects.show', compact('project', 'milestones', 'tasks'));
    }
}
