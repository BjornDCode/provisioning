<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InvitationsController extends Controller
{
    
    public function store(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $team->invitations()->create([
            'email' => $request->input('email'),
            'token' => Str::random(32),
        ]);

        return Redirect::route('settings.teams.show', [
            'team' => $team->id,
        ])->with('message', "{$request->input('email')} was invited!");
    }

}
