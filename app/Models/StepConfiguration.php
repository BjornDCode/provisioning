<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepConfiguration extends Model
{
    use HasFactory;

    protected $casts = [
        'details' => 'array',
    ];

    protected $fillable = [
        'pipeline_id',
        'type',
        'details',
    ];

}
