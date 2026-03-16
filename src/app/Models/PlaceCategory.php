<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaceCategory extends Model
{
    protected $fillable = ['name', 'slug', 'sort_order', 'map_pin_icon', 'map_pin_color'];

    public function places(): HasMany
    {
        return $this->hasMany(Place::class, 'place_category_id');
    }
}
