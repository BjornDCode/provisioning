<?php

namespace App\Models\Billing;

use Carbon\Carbon;
use App\Models\Account\Team;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'subscription_id',
        'plan_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getPaidAttribute()
    {
        return match($this->plan_id) {
            Config::get('services.stripe.free_plan_id') => false,
            Config::get('services.stripe.paid_plan_id') => true,
            default => false,
        };
    }

}
