<?php

namespace App\Http\Controllers\Pipeline;

use Inertia\Inertia;
use App\Models\Pipeline\Pipeline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Flows\Factory as FlowFactory;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\Pipeline\PipelineResource;
use App\Http\Requests\Pipeline\CreatePipelineRequest;

class PipelineController extends Controller
{

    public function index()
    {
        return Inertia::render('Pipeline/Index', [
            'pipelines' => PipelineResource::collection(
                Auth::user()->currentTeam->pipelines
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Pipeline/Create');
    }
    
    public function store(CreatePipelineRequest $request)
    {
        $pipeline = Pipeline::create([
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'team_id' => Auth::user()->currentTeam->id,
        ]);

        $flow = FlowFactory::create($pipeline);

        return Redirect::route('steps.configuration.render', [
            'pipeline' => $pipeline->id,
            'step' => $flow->next()->type(),
        ]);
    }

}
