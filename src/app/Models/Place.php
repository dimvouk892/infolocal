<?php

namespace App\Models;

use App\Services\ImageUploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Place extends Model
{
    protected $fillable = [
        'title', 'title_el', 'slug', 'featured_image', 'gallery', 'short_description', 'short_description_el', 'full_content', 'full_content_el',
        'place_category_id', 'coordinates', 'address', 'video_url', 'phone', 'email', 'website',
        'status', 'seo_title', 'seo_description', 'sort_order', 'featured',
    ];

    protected $casts = [
        'gallery' => 'array',
        'coordinates' => 'array',
        'featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'place_category_id');
    }

    public function relatedPlaces(): BelongsToMany
    {
        return $this->belongsToMany(Place::class, 'place_place', 'place_id', 'related_place_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function nearbyBusinesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'place_business', 'place_id', 'business_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function villages(): BelongsToMany
    {
        return $this->belongsToMany(Village::class, 'place_village', 'place_id', 'village_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Map location for frontend (lat/lng). */
    public function getMapLocationAttribute(): ?array
    {
        $coords = $this->coordinates;
        if (is_array($coords) && isset($coords['lat'], $coords['lng'])) {
            return ['lat' => (float) $coords['lat'], 'lng' => (float) $coords['lng']];
        }
        return null;
    }

    /** YouTube or Vimeo URL to embed URL for iframe, or null if not supported. */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        $url = $this->video_url ?? '';
        if (empty($url)) {
            return null;
        }
        if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]+)#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }
        return null;
    }

    /** Translated title (Greek when locale is el and title_el is set). */
    public function getTitleAttribute($value): string
    {
        if (app()->getLocale() === 'el') {
            $el = trim((string) ($this->attributes['title_el'] ?? ''));
            if ($el !== '') {
                return $el;
            }
        }
        return (string) $value;
    }

    /** Translated short description. */
    public function getShortDescriptionAttribute($value): ?string
    {
        if (app()->getLocale() === 'el') {
            $el = trim((string) ($this->attributes['short_description_el'] ?? ''));
            if ($el !== '') {
                return $el;
            }
        }
        return $value !== null ? (string) $value : null;
    }

    /** Translated full content. */
    public function getFullContentAttribute($value): ?string
    {
        if (app()->getLocale() === 'el') {
            $el = trim((string) ($this->attributes['full_content_el'] ?? ''));
            if ($el !== '') {
                return $el;
            }
        }
        return $value !== null ? (string) $value : null;
    }

    /** For frontend compatibility with destination-card (name, image, excerpt, tagline) */
    public function getNameAttribute(): string
    {
        return $this->getAttribute('title');
    }

    public function getImageAttribute(): ?string
    {
        return $this->getAttribute('featured_image');
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->getAttribute('short_description');
    }

    public function getTaglineAttribute(): ?string
    {
        $short = $this->short_description;
        if (! $short) {
            return null;
        }
        $first = explode("\n", $short)[0];
        return \Illuminate\Support\Str::limit($first, 80);
    }

    protected static function booted(): void
    {
        static::deleting(function (Place $place) {
            $service = app(ImageUploadService::class);
            $service->delete($place->featured_image);
            $service->deleteMany($place->gallery ?? []);
        });
    }
}
