<?php

namespace App\Models;

use App\Enums\StepType;
use App\Models\Account\Team;
use App\Models\StepConfiguration;
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

    public function configs()
    {
        return $this->hasMany(StepConfiguration::class);
    }

    public function getConfig(StepType $type): StepConfiguration|null
    {
        return $this->configs()->where('type', $type->toString())->first();
    }

    public function hasConfig(StepType $type): bool
    {
        return !is_null($this->getConfig($type));
    }

    public function getGitProviderAttribute()
    {
        $config = $this->getConfig(
            StepType::fromString(
                StepType::GIT_PROVIDER
            )
        );

        return $config?->details['value'];
    }

}
