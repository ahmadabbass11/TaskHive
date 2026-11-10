<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    // Display teams and team creation form
    public function index()
    {
        $user = Auth::user();
        $teams = $user->teams()->with('teamAdmin')->get();
        $allUsers = User::where('id', '!=', $user->id)->get();

        return view('teams.index', compact('teams', 'allUsers'));
    }

    // Create a new team
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'description' => 'required|string',
            'members' => 'required|array',
            'members.*' => 'exists:users,id',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'team_admin' => Auth::id(),
        ]);

        $team->users()->attach($request->members);
        $team->users()->attach(Auth::id()); // Ensure the creator is added to the team

        return redirect()->route('teams.index')->with('success', 'Team created successfully!');
    }

    // Add a member to an existing team
    public function addMember(Request $request, Team $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if (!$team->users()->where('users.id', $request->user_id)->exists()) {
            $team->users()->attach($request->user_id);
            return back()->with('success', 'Member added successfully!');
        }

        return back()->with('error', 'User is already in the team.');
    }

    public function removeMember(Team $team, User $user)
    {
        if ($team->team_admin === $user->id) {
            return back()->with('error', 'You cannot remove the team admin.');
        }

        if ($team->users()->detach($user->id)) {
            return back()->with('success', 'Member removed successfully!');
        }

        return back()->with('error', 'Something went wrong.');
    }

    public function updateRole(Team $team, User $user)
    {
        // Only the main team admin can update roles
        if (Auth::id() !== $team->team_admin) {
            return redirect()->route('teams.index')->with('error', 'Only the team admin can change roles.');
        }

        // Prevent changing the main admin's role
        if ($user->id == $team->team_admin) {
            return redirect()->route('teams.index')->with('error', 'Cannot change the main admin role.');
        }

        // Toggle role between 'admin' and 'user'
        $user->role = ($user->role == 'admin') ? 'user' : 'admin';
        $user->save();

        return redirect()->route('teams.index')->with('success', 'User role updated successfully.');
    }

    // Delete a team (Only the admin can delete)
    public function destroy(Team $team)
    {
        if (Auth::id() !== $team->team_admin) {
            return back()->with('error', 'Only the team admin can delete the team.');
        }

        $team->users()->detach();
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully!');
    }
}
