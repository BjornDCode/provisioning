<?php

namespace App\Models\Pipeline;

use App\Models\Pipeline\Step;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function steps()
    {
        return $this->hasMany(Step::class, 'config_id');
    }

}
