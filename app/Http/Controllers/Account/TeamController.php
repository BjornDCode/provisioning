<?php

namespace App\Http\Controllers\Account;

use Inertia\Inertia;
use App\Models\Account\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Account\TeamResource;
use App\Http\Requests\Account\CreateTeamRequest;
use App\Http\Resources\Account\InvitationResource;

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
            'team' => new TeamResource($team),
            'members' => UserResource::collection($team->members),
            'invitations' => InvitationResource::collection($team->invitations),
        ]);
    }

    public function store(CreateTeamRequest $request)
    {
        $team = Auth::user()->teams()->create(
            $request->validated(),
        );

        Auth::user()->setCurrentTeam($team);

        return Redirect::route('settings.teams.show', [
            'team' => $team->id,
        ]);
    }

}
