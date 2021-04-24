<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CreateInvitationRequest;

class InvitationsController extends Controller
{
    
    public function store(CreateInvitationRequest $request, Team $team)
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
