<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MembershipsController extends Controller
{
    
    public function store(Request $request, Team $team)
    {
        $team->join(Auth::user());

        $invitation = Invitation::where('token', $request->input('token'));
        $invitation->delete();

        Auth::user()->setCurrentTeam($team);

        return Redirect::route('dashboard');
    }

}
