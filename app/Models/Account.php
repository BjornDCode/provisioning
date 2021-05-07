<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'type',
        'token',
        'refresh_token',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
