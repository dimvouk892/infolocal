<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\UpdateBusinessRequest;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessReview;
use App\Services\HtmlSanitizer;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const IMAGE_DIR = 'businesses';
    public function index(Request $request): View
    {
        $businesses = $request->user()->businesses()
            ->with(['subscriptions' => fn ($q) => $q->latest()->limit(1)])
            ->get();

        return view('dashboard.index', compact('businesses'));
    }

    public function editBusiness(Request $request, Business $business): View|RedirectResponse
    {
        if (! $request->user()->businesses->contains($business)) {
            abort(403);
        }
        if (! $business->hasActiveSubscription()) {
            return redirect()->route('dashboard.index')
                ->with('error', __('Subscription expired for this business. Renew to manage it.'));
        }

        $categories = BusinessCategory::orderBy('sort_order')->get();
        $villages = \App\Models\Village::orderBy('sort_order')->orderBy('name')->get();

        return view('dashboard.business.edit', compact('business', 'categories', 'villages'));
    }

    public function updateBusiness(UpdateBusinessRequest $request, Business $business): RedirectResponse
    {
        if (! $request->user()->businesses->contains($business)) {
            abort(403);
        }
        if (! $business->hasActiveSubscription()) {
            return redirect()->route('dashboard.index')
                ->with('error', __('Subscription expired for this business. Renew to manage it.'));
        }

        $validated = $request->validated();

        if ($request->hasFile('featured_image')) {
            $business->featured_image = app(ImageUploadService::class)
                ->replace($business->featured_image, $request->file('featured_image'), self::IMAGE_DIR);
        }
        if ($request->hasFile('logo')) {
            $business->logo = app(ImageUploadService::class)
                ->replace($business->logo, $request->file('logo'), self::IMAGE_DIR);
        }
        $service = app(ImageUploadService::class);
        $oldGallery = $business->gallery ?? [];
        $keep = array_values(array_filter(
            $request->input('gallery_keep', []),
            fn ($p) => is_string($p) && trim($p) !== ''
        ));
        $newPaths = $service->uploadMany($request->file('gallery') ?? [], self::IMAGE_DIR);
        $business->gallery = array_values(array_filter(
            array_merge($keep, $newPaths),
            fn ($p) => is_string($p) && trim($p) !== ''
        ));
        foreach ($oldGallery as $path) {
            if (! in_array($path, $business->gallery, true)) {
                $service->delete($path);
            }
        }

        $business->name = $validated['name'];
        $business->description = isset($validated['description'])
            ? HtmlSanitizer::sanitize($validated['description'])
            : null;
        $business->video_url = $validated['video_url'] ?? null;
        $business->address = $validated['address'] ?? null;
        $business->map_location = $this->buildMapLocation($validated['map_lat'] ?? null, $validated['map_lng'] ?? null);
        $business->phone = $validated['phone'] ?? null;
        $business->email = $validated['email'] ?? null;
        $business->website = $validated['website'] ?? null;
        $business->opening_hours = ! empty($validated['opening_hours'])
            ? ['description' => $validated['opening_hours']]
            : null;
        $social = array_filter([
            'facebook' => $validated['facebook'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'tripadvisor' => $validated['tripadvisor'] ?? null,
        ]);
        $business->social_links = ! empty($social) ? $social : null;
        $business->reviews_enabled = $request->boolean('reviews_enabled');
        $business->reviews_require_approval = $request->boolean('reviews_require_approval');
        $villageId = $request->input('village_id');
        $business->villages()->sync($villageId ? [$villageId] : []);
        $business->save();

        return redirect()->route('dashboard.business.edit', $business)->with('success', __('messages.dashboard.business_updated'));
    }

    private function buildMapLocation($lat, $lng): ?array
    {
        $lat = $lat !== null && $lat !== '' ? (float) $lat : null;
        $lng = $lng !== null && $lng !== '' ? (float) $lng : null;
        if ($lat === null || $lng === null) {
            return null;
        }
        return ['lat' => $lat, 'lng' => $lng];
    }

    public function subscription(Request $request): View
    {
        $businesses = $request->user()->businesses()->with(['subscriptions' => fn ($q) => $q->latest()->limit(1)->with('plan')])->get();

        return view('dashboard.subscription', compact('businesses'));
    }

    public function businessReviews(Request $request): View|RedirectResponse
    {
        $businesses = $request->user()->businesses;
        if ($businesses->isEmpty()) {
            return redirect()->route('dashboard.index')
                ->with('info', __('messages.subscription.no_business_assigned'));
        }

        $status = $request->string('status')->toString();
        $reviews = BusinessReview::query()
            ->whereIn('business_id', $businesses->pluck('id'))
            ->when($status === 'approved', fn ($q) => $q->where('is_approved', true))
            ->when($status === 'hidden', fn ($q) => $q->where('is_approved', false))
            ->with('business:id,name')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.reviews.index', [
            'businesses' => $businesses,
            'reviews' => $reviews,
            'status' => $status,
        ]);
    }

    public function updateBusinessReview(Request $request, BusinessReview $businessReview): RedirectResponse
    {
        if (! $request->user()->businesses->pluck('id')->contains($businessReview->business_id)) {
            abort(403);
        }

        $request->validate(['is_approved' => ['required', 'boolean']]);
        $businessReview->update(['is_approved' => (bool) $request->input('is_approved')]);

        return redirect()
            ->route('dashboard.business.reviews.index', request()->only('status'))
            ->with('success', __('messages.reviews.visibility_updated'));
    }

    public function destroyBusinessReview(Request $request, BusinessReview $businessReview): RedirectResponse
    {
        if (! $request->user()->businesses->pluck('id')->contains($businessReview->business_id)) {
            abort(403);
        }

        $businessReview->delete();

        return redirect()
            ->route('dashboard.business.reviews.index', request()->only('status'))
            ->with('success', __('messages.reviews.deleted'));
    }
}
