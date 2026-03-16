<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Village;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlaceController extends Controller
{
    private const IMAGE_DIR = 'places';

    public function index(): View
    {
        $query = Place::with('category')->ordered();

        if (Schema::hasColumn('places', 'featured')) {
            $query->orderByDesc('featured');
        }

        $places = $query->get();

        return view('admin.places.index', compact('places'));
    }

    public function create(): View
    {
        $categories = PlaceCategory::orderBy('sort_order')->orderBy('name')->get();
        $villages = Village::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.places.create', compact('categories', 'villages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(false));

        $validated['slug'] = $this->makeSlugUnique(Str::slug($validated['title']));
        $validated['status'] = $request->input('status', 'draft');

        if (Schema::hasColumn('places', 'featured')) {
            $validated['featured'] = $request->boolean('featured');
        }

        $validated['coordinates'] = $this->buildCoordinates(
            $request->input('map_lat'),
            $request->input('map_lng')
        );

        unset($validated['map_lat'], $validated['map_lng']);

        $validated['featured_image'] = $request->hasFile('featured_image')
            ? app(ImageUploadService::class)->upload($request->file('featured_image'), self::IMAGE_DIR)
            : null;

        $validated['gallery'] = app(ImageUploadService::class)->uploadMany(
            $request->file('gallery') ?? [],
            self::IMAGE_DIR
        );

        $villageId = $request->input('village_id');
        unset($validated['village_id']);
        $validated = $this->onlyExistingPlaceColumns($validated);

        $place = Place::create($validated);
        $place->villages()->sync($villageId ? [$villageId] : []);

        return redirect()
            ->route('admin.places.index')
            ->with('success', __('Place created.'));
    }

    public function edit(Place $place): View
    {
        $categories = PlaceCategory::orderBy('sort_order')->orderBy('name')->get();
        $villages = Village::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.places.edit', compact('place', 'categories', 'villages'));
    }

    public function update(Request $request, Place $place): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(false));

        $validated['slug'] = $this->makeSlugUnique(Str::slug($validated['title']), $place->id);
        $validated['status'] = $request->input('status', 'draft');

        if (Schema::hasColumn('places', 'featured')) {
            $validated['featured'] = $request->boolean('featured');
        }

        $validated['coordinates'] = $this->buildCoordinates(
            $request->input('map_lat'),
            $request->input('map_lng')
        );

        unset($validated['map_lat'], $validated['map_lng']);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = app(ImageUploadService::class)->replace(
                $place->featured_image,
                $request->file('featured_image'),
                self::IMAGE_DIR
            );
        } elseif ($request->filled('featured_image_path')) {
            $path = ltrim($request->input('featured_image_path'), '/');
            if (! str_contains($path, '..') && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true)) {
                    $validated['featured_image'] = $path;
                }
            }
        }
        if (! isset($validated['featured_image'])) {
            unset($validated['featured_image']);
        }

        $service = app(ImageUploadService::class);
        $keep = array_values($request->input('gallery_keep', []));
        $newPaths = $service->uploadMany($request->file('gallery') ?? [], self::IMAGE_DIR);
        $validated['gallery'] = array_merge($keep, $newPaths);

        $oldGallery = $place->gallery ?? [];
        foreach ($oldGallery as $path) {
            if (! in_array($path, $validated['gallery'], true)) {
                $service->delete($path);
            }
        }

        unset($validated['gallery_keep'], $validated['gallery_remove']);
        $villageId = $request->input('village_id');
        unset($validated['village_id']);

        $validated = $this->onlyExistingPlaceColumns($validated);

        $place->update($validated);
        $place->villages()->sync($villageId ? [$villageId] : []);

        return redirect()
            ->route('admin.places.index')
            ->with('success', __('Place updated.'));
    }

    public function destroy(Place $place): RedirectResponse
    {
        $place->delete();

        return redirect()
            ->route('admin.places.index')
            ->with('success', __('Place deleted.'));
    }

    private function validationRules(bool $requireFeaturedImage): array
    {
        $imageRules = $requireFeaturedImage
            ? ImageUploadService::validationRules(true, 'featured_image')
            : ImageUploadService::validationRules(false, 'featured_image');

        return array_merge([
            'title' => ['required', 'string', 'max:255'],
            'title_el' => ['nullable', 'string', 'max:255'],
            'place_category_id' => ['nullable', 'exists:place_categories,id'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'short_description_el' => ['nullable', 'string', 'max:1000'],
            'full_content' => ['nullable', 'string', 'max:10000'],
            'full_content_el' => ['nullable', 'string', 'max:10000'],
            'address' => ['nullable', 'string', 'max:255'],
            'video_url' => ['nullable', 'string', 'url', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'map_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'map_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:draft,published'],
            'featured' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'gallery_keep' => ['nullable', 'array'],
            'gallery_keep.*' => ['string'],
            'gallery_remove' => ['nullable', 'array'],
            'gallery_remove.*' => ['string'],
            'village_id' => ['nullable', 'integer', 'exists:villages,id'],
        ], $imageRules, ImageUploadService::galleryValidationRules('gallery'));
    }

    /** Remove columns that may not exist if migration has not run (address, video_url, etc.). */
    private function onlyExistingPlaceColumns(array $validated): array
    {
        $migrationColumns = ['address', 'video_url', 'phone', 'email', 'website', 'featured', 'title_el', 'short_description_el', 'full_content_el'];

        foreach ($migrationColumns as $column) {
            if (array_key_exists($column, $validated) && ! Schema::hasColumn('places', $column)) {
                unset($validated[$column]);
            }
        }

        return $validated;
    }

    private function buildCoordinates(?string $lat, ?string $lng): ?array
    {
        $lat = $lat !== null && $lat !== '' ? (float) $lat : null;
        $lng = $lng !== null && $lng !== '' ? (float) $lng : null;

        if ($lat === null || $lng === null) {
            return null;
        }

        return [
            'lat' => $lat,
            'lng' => $lng,
        ];
    }

    private function makeSlugUnique(string $slug, ?int $excludeId = null): string
    {
        $slug = $slug !== '' ? $slug : 'place';

        $query = Place::where('slug', $slug);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        if (! $query->exists()) {
            return $slug;
        }

        $suffix = 2;

        do {
            $candidate = $slug . '-' . $suffix;
            $q = Place::where('slug', $candidate);

            if ($excludeId !== null) {
                $q->where('id', '!=', $excludeId);
            }

            $suffix++;
        } while ($q->exists());

        return $candidate;
    }
}