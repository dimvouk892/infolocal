<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBusinessRequest;
use App\Http\Requests\Admin\UpdateBusinessRequest;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\User;
use App\Models\Village;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BusinessController extends Controller
{
    private const IMAGE_DIR = 'businesses';

    public function index(): View
    {
        $businesses = Business::with(['category', 'owner'])->orderBy('name')->get();
        return view('admin.businesses.index', compact('businesses'));
    }

    public function create(): View
    {
        $categories = BusinessCategory::orderBy('sort_order')->get();
        $users = User::where('role', User::ROLE_USER)->orderBy('name')->get();
        $villages = Village::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.businesses.create', compact('categories', 'users', 'villages'));
    }

    public function store(StoreBusinessRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $this->makeSlugUnique(Str::slug($validated['name']));
        $validated['featured'] = $request->boolean('featured');
        $validated['reviews_enabled'] = $request->boolean('reviews_enabled');
        $validated['reviews_require_approval'] = $request->boolean('reviews_require_approval');

        $validated['featured_image'] = app(ImageUploadService::class)
            ->upload($request->file('featured_image'), self::IMAGE_DIR);
        if ($request->hasFile('logo')) {
            $validated['logo'] = app(ImageUploadService::class)->upload($request->file('logo'), self::IMAGE_DIR);
        }
        $validated['gallery'] = app(ImageUploadService::class)->uploadMany($request->file('gallery') ?? [], self::IMAGE_DIR);
        $validated['map_location'] = $this->buildMapLocation($request->input('map_lat'), $request->input('map_lng'));
        unset($validated['map_lat'], $validated['map_lng']);
        $validated['social_links'] = array_filter([
            'facebook' => $validated['facebook'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'tripadvisor' => $validated['tripadvisor'] ?? null,
        ]) ?: null;
        unset($validated['facebook'], $validated['instagram'], $validated['tripadvisor']);
        $villageId = $request->input('village_id');
        unset($validated['village_id']);
        $business = Business::create($validated);
        $business->villages()->sync($villageId ? [$villageId] : []);
        return redirect()->route('admin.businesses.index')->with('success', __('Business created.'));
    }

    public function edit(Business $business): View
    {
        $categories = BusinessCategory::orderBy('sort_order')->get();
        $users = User::where('role', User::ROLE_USER)->orderBy('name')->get();
        $villages = Village::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.businesses.edit', compact('business', 'categories', 'users', 'villages'));
    }

    public function update(UpdateBusinessRequest $request, Business $business): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $this->makeSlugUnique(Str::slug($validated['name']), $business->id);
        $validated['featured'] = $request->boolean('featured');
        $validated['reviews_enabled'] = $request->boolean('reviews_enabled');
        $validated['reviews_require_approval'] = $request->boolean('reviews_require_approval');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = app(ImageUploadService::class)
                ->replace($business->featured_image, $request->file('featured_image'), self::IMAGE_DIR);
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
        if ($request->hasFile('logo')) {
            $validated['logo'] = app(ImageUploadService::class)
                ->replace($business->logo, $request->file('logo'), self::IMAGE_DIR);
        } elseif ($request->filled('logo_path')) {
            $path = ltrim($request->input('logo_path'), '/');
            if (! str_contains($path, '..') && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true)) {
                    $validated['logo'] = $path;
                }
            }
        }
        if (! isset($validated['logo'])) {
            unset($validated['logo']);
        }
        $service = app(ImageUploadService::class);
        $keep = array_values($request->input('gallery_keep', []));
        $newPaths = $service->uploadMany($request->file('gallery') ?? [], self::IMAGE_DIR);
        $validated['gallery'] = array_merge($keep, $newPaths);
        $oldGallery = $business->gallery ?? [];
        foreach ($oldGallery as $path) {
            if (! in_array($path, $validated['gallery'], true)) {
                $service->delete($path);
            }
        }
        unset($validated['gallery_keep']);
        $validated['map_location'] = $this->buildMapLocation($request->input('map_lat'), $request->input('map_lng'));
        unset($validated['map_lat'], $validated['map_lng']);
        $validated['social_links'] = array_filter([
            'facebook' => $validated['facebook'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'tripadvisor' => $validated['tripadvisor'] ?? null,
        ]) ?: null;
        unset($validated['facebook'], $validated['instagram'], $validated['tripadvisor']);
        $villageId = $request->input('village_id');
        unset($validated['village_id']);
        $business->update($validated);
        $business->villages()->sync($villageId ? [$villageId] : []);
        return redirect()->route('admin.businesses.index')->with('success', __('Business updated.'));
    }

    public function destroy(Business $business): RedirectResponse
    {
        $business->delete();
        return redirect()->route('admin.businesses.index')->with('success', __('Business deleted.'));
    }

    private function buildMapLocation(?string $lat, ?string $lng): ?array
    {
        $lat = $lat !== null && $lat !== '' ? (float) $lat : null;
        $lng = $lng !== null && $lng !== '' ? (float) $lng : null;
        if ($lat === null || $lng === null) {
            return null;
        }
        return ['lat' => $lat, 'lng' => $lng];
    }

    private function makeSlugUnique(string $slug, ?int $excludeId = null): string
    {
        $query = Business::where('slug', $slug);
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }
        if (! $query->exists()) {
            return $slug;
        }
        $suffix = 2;
        do {
            $candidate = $slug . '-' . $suffix;
            $q = Business::where('slug', $candidate);
            if ($excludeId !== null) {
                $q->where('id', '!=', $excludeId);
            }
            $suffix++;
        } while ($q->exists());
        return $candidate;
    }
}
