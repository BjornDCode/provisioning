<?php

namespace App\Http\Controllers\Account;

use Inertia\Inertia;
use App\Models\Account\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTeamRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\InvitationResource;

class TeamController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Account/Teams/Index', [
            'owned' => TeamResource::collection($user->teams),
            'memberships' => TeamResource::collection($user->memberships),
        ]);
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        return Inertia::render('Account/Teams/Show', [
            'members' => UserResource::collection($team->members),
            'invitations' => InvitationResource::collection($team->invitations),
        ]);
    }

    public function store(CreateTeamRequest $request)
    {
        $team = Auth::user()->teams()->create(
            $request->input(),
        );

        return Redirect::route('settings.teams.show', [
            'team' => $team->id,
        ]);
    }

}
