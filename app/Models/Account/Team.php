<?php

namespace App\Models\Account;

use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Models\Billing\Plan;
use App\Models\Pipeline\Pipeline;
use App\Models\Account\Invitation;
use App\Models\Account\Membership;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ExistingMemberException;
use App\Exceptions\ExistingInvitationException;
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

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function pipelines()
    {
        return $this->hasMany(Pipeline::class);
    }

    public function plan()
    {
        return $this->hasOne(Plan::class);
    }

    public function hasMember(User $user): bool
    {
        if ($this->owner->is($user)) {
            return true;
        }

        return $this->members->contains($user);
    }

    public function hasInvitation(string $email): bool
    {
        return $this->invitations()->where('email', $email)->exists();
    }

    public function join(User $user): void
    {
        $this->members()->attach($user);
    }

    public function invite($email)
    {
        if ($this->hasInvitation($email)) {
            throw new ExistingInvitationException();
        }

        $user = User::where('email', $email)->first();

        if (!is_null($user) && $this->hasMember($user)) {
            throw new ExistingMemberException();
        }

        return $this->invitations()->create([
            'email' => $email,
            'token' => Str::random(32),
        ]);
    }

}
