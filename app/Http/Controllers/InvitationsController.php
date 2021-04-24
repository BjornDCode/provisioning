<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    
    public function store(Team $team)
    {
        $this->authorize('update', $team);
    }

}
