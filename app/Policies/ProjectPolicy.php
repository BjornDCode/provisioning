<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Project $project)
    {
        return $project->team->hasMember($user);
    }
}
