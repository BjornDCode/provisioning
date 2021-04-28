<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProjectResource;
use App\Http\Requests\CreateProjectRequest;

class ProjectController extends Controller
{

    public function index()
    {
        return Inertia::render('Pipeline/Projects/Index', [
            'projects' => ProjectResource::collection(
                Auth::user()->currentTeam->projects
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Pipeline/Projects/Create');
    }
    
    public function store(CreateProjectRequest $request)
    {
        $project = Project::create([
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'team_id' => Auth::user()->currentTeam->id,
        ]);
    }

}
