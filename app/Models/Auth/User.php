<?php

namespace App\Models\Auth;

use App\Models\Account\Team;
use App\Models\Account\Membership;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_team_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function memberships()
    {
        return $this->belongsToMany(Team::class, 'memberships')->using(Membership::class);
    }

    public function getAllTeamsAttribute()
    {
        return $this->teams->merge($this->memberships);
    }

    public function setCurrentTeam(Team $team): void
    {
        $this->current_team_id = $team->id;
        $this->save();
    }
}
