<?php

namespace App\Http\Controllers\Pipeline;

use Inertia\Inertia;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StepController extends Controller
{
    
    public function configure(Project $project)
    {
        $this->authorize('update', $project);

        return Inertia::render('Pipeline/Steps/Configure');
    }

}
