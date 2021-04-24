<?php

namespace App\Policies;

use App\Models\Account\Team;
use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
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

    public function view(User $user, Team $team)
    {
        return $team->owner->is($user);
    }

    public function update(User $user, Team $team)
    {
        return $team->owner->is($user);
    }

}
