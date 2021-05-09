<?php

namespace App\Models\Pipeline;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pipeline\StepConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Step extends Model
{
    use HasFactory;

    public function config()
    {
        return $this->belongsTo(StepConfiguration::class, 'config_id');
    }

}
