<?php

namespace App\Http\Controllers\Account;

use App\Mail\Invited;
use Illuminate\Support\Str;
use App\Models\Account\Team;
use App\Models\Account\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Account\CreateInvitationRequest;

class InvitationsController extends Controller
{
    
    public function store(CreateInvitationRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        $invitation = $team->invitations()->create([
            'email' => $request->input('email'),
            'token' => Str::random(32),
        ]);

        Mail::to($request->user())
            ->queue(new Invited($team, $invitation));

        return Redirect::route('settings.teams.show', [
            'team' => $team->id,
        ])->with('message', "{$request->input('email')} was invited!");
    }

    public function destroy(Team $team, Invitation $invitation)
    {
        $this->authorize('update', $team);

        $invitation->delete();

        return Redirect::route('settings.teams.show', [
            'team' => $team->id,
        ])->with('message', "Invitation deleted.");
    }

}
