<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Village extends Model
{
    protected $fillable = [
        'name',
        'name_el',
        'slug',
        'sort_order',
    ];

    /**
     * Return the name in the current locale (name_el when locale is 'el' and name_el is set, else name).
     */
    public function getNameAttribute(?string $value): string
    {
        if (app()->getLocale() === 'el' && !empty($this->attributes['name_el'] ?? null)) {
            return (string) $this->attributes['name_el'];
        }
        return (string) ($value ?? '');
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_village', 'village_id', 'business_id');
    }

    public function places(): BelongsToMany
    {
        return $this->belongsToMany(Place::class, 'place_village', 'village_id', 'place_id');
    }
}
