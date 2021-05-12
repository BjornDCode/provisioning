<?php

namespace App\Models\Billing;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'subscription_id',
        'expires_at',
    ];

    public function getActiveAttribute()
    {
        if (is_null($this->expires_at)) {
            return true;
        } 

        $expires_at = $this->expires_at instanceof Carbon ? $this->expires_at : Carbon::parse($this->expires_at);

        return $expires_at->greaterThan(Carbon::now());
    }

}
