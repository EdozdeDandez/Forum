<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $activities = $user->activity()->latest()->with('subject')->get()->groupBy(function($activity)
        {
            return $activity->created_at->format('Y-m-d');
        });
        return view('profiles.show', ['profileUser' => $user,
            'threads' => $user->threads()->paginate(30),
            'activities' => $activities
        ]);
    }
}
