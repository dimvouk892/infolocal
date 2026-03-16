<?php

namespace App\Models;

use App\Services\ImageUploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $fillable = [
        'name', 'name_el', 'slug', 'business_category_id', 'description', 'description_el', 'video_url', 'content_blocks',
        'logo', 'featured_image', 'gallery', 'address', 'phone', 'email', 'website',
        'opening_hours', 'map_location', 'social_links', 'status', 'owner_id', 'featured', 'show_title_on_card', 'show_category_on_card', 'reviews_enabled', 'reviews_require_approval',
    ];

    protected $casts = [
        'gallery' => 'array',
        'opening_hours' => 'array',
        'map_location' => 'array',
        'social_links' => 'array',
        'content_blocks' => 'array',
        'featured' => 'boolean',
        'show_title_on_card' => 'boolean',
        'show_category_on_card' => 'boolean',
        'reviews_enabled' => 'boolean',
        'reviews_require_approval' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(BusinessSubscription::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BusinessReview::class)->latest();
    }

    public function activeSubscription(): HasMany
    {
        return $this->hasMany(BusinessSubscription::class)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString());
    }

    public function places(): BelongsToMany
    {
        return $this->belongsToMany(Place::class, 'place_business', 'business_id', 'place_id')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function villages(): BelongsToMany
    {
        return $this->belongsToMany(Village::class, 'business_village', 'business_id', 'village_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /** Businesses that have valid map coordinates (show on map page) */
    public function scopeOnMap($query)
    {
        return $query->whereNotNull('map_location');
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /** Translated name (Greek when locale is el and name_el is set). */
    public function getNameAttribute($value): string
    {
        if (app()->getLocale() === 'el') {
            $el = trim((string) ($this->attributes['name_el'] ?? ''));
            if ($el !== '') {
                return $el;
            }
        }
        return (string) $value;
    }

    /** Translated description. */
    public function getDescriptionAttribute($value): ?string
    {
        if (app()->getLocale() === 'el') {
            $el = trim((string) ($this->attributes['description_el'] ?? ''));
            if ($el !== '') {
                return $el;
            }
        }
        return $value !== null ? (string) $value : null;
    }

    /** For frontend compatibility (business-card uses title) */
    public function getTitleAttribute(): string
    {
        return $this->getAttribute('name');
    }

    public function getCategoryNameAttribute(): ?string
    {
        return $this->category?->name;
    }

    public function getOpeningHoursDisplayAttribute(): ?string
    {
        $hours = $this->opening_hours;
        if (is_string($hours)) {
            return $hours;
        }
        if (is_array($hours) && ! empty($hours)) {
            return $hours['description'] ?? implode(' | ', $hours);
        }
        return null;
    }

    /** YouTube or Vimeo URL to embed URL for iframe, or null if not supported */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        $url = $this->video_url;
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

    /**
     * Public URL for featured image or null. Use placeholder when missing.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (empty($this->featured_image)) {
            return null;
        }
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->featured_image);
    }

    protected static function booted(): void
    {
        static::deleting(function (Business $business) {
            $service = app(ImageUploadService::class);
            $service->delete($business->featured_image);
            $service->delete($business->logo);
            $service->deleteMany($business->gallery ?? []);
        });
    }
}
