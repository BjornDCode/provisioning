<?php

namespace App\Http\Controllers\Account;

use App\Models\Account\Team;
use Illuminate\Http\Request;
use App\Models\Account\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MembershipsController extends Controller
{
    
    public function store(Request $request, Team $team)
    {
        $invitation = Invitation::where('token', $request->input('token'))->firstOrFail();

        if (!$invitation->team()->is($team)) {
            abort(404);
        }

        $team->join(Auth::user());

        $invitation->delete();

        Auth::user()->setCurrentTeam($team);

        return Redirect::route('dashboard');
    }

}
