<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Membership extends Pivot
{
    use HasFactory;

    protected $table = 'memberships';
}
