<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'price', 'duration', 'duration_days', 'is_active', 'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function businessSubscriptions(): HasMany
    {
        return $this->hasMany(BusinessSubscription::class, 'subscription_plan_id');
    }
}
