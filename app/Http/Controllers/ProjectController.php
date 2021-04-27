<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateProjectRequest;

class ProjectController extends Controller
{
    
    public function store(CreateProjectRequest $request)
    {
        $project = Project::create([
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'team_id' => Auth::user()->currentTeam->id,
        ]);
    }

}
