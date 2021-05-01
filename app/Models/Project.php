<?php

namespace App\Models;

use App\Models\Account\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class); 
    }

}
