<?php

namespace App\Models;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships')->using(Membership::class);
    }

    public function hasMember(User $user): bool
    {
        if ($this->owner->is($user)) {
            return true;
        }

        return $this->members->contains($user);
    }

    public function join(User $user): void
    {
        $this->members()->attach($user);
    }

}
