<?php

namespace App\Policies;

use App\Models\Pipeline;
use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PipelinePolicy
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

    public function update(User $user, Pipeline $pipeline)
    {
        return $pipeline->team->hasMember($user);
    }
}
