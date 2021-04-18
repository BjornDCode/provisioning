<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\TeamResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTeamRequest;
use Illuminate\Support\Facades\Redirect;

class TeamController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Account/Teams/Index', [
            'teams' => TeamResource::collection($user->teams),
            'memberships' => TeamResource::collection($user->memberships),
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
