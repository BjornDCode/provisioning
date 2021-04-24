<?php

namespace App\Models\Account;

use App\Models\Account\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
