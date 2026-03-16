<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessCategory extends Model
{
    protected $fillable = ['name', 'name_el', 'slug', 'sort_order', 'map_pin_icon', 'map_pin_color'];

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class, 'business_category_id');
    }

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
}
