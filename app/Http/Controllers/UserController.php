<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->input('term');
        $currentUser = auth()->user();

        // Exclude the current user so they cannot search themselves
        $users = User::where('id', '!=', $currentUser->id)
            ->where('name', 'LIKE', "%{$term}%")
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    public function getAdminPage(Request $request)
    {
        return view("admin.index");
    }


}

