<?php

namespace App\Http\Controllers\Pipeline;

use Inertia\Inertia;
use App\Models\Project;
use App\Enums\ProjectType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Flows\Factory as FlowFactory;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Redirect;
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

        $flow = FlowFactory::create(
            ProjectType::fromString(
                $request->input('type')
            )
        );

        return Redirect::route('steps.configure', [
            'project' => $project->id,
            'step' => $flow->next()->type(),
        ]);
    }

}
