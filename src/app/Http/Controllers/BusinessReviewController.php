<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessReview;
use App\Services\ProfanityFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BusinessReviewController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $business = Business::published()->where('slug', $slug)->first();

        if (! $business) {
            return redirect()
                ->back()
                ->with('error', 'Business not found.');
        }

        if (! $business->reviews_enabled) {
            return redirect()
                ->to(route('businesses.show', $business->slug) . '#reviews')
                ->with('error', 'Reviews are currently disabled for this business.');
        }

        $validated = $request->validate([
            'reviewer_name' => ['required', 'string', 'max:120'],
            'reviewer_email' => ['required', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $email = strtolower(trim($validated['reviewer_email']));
        $alreadyReviewed = BusinessReview::where('business_id', $business->id)
            ->whereRaw('LOWER(TRIM(reviewer_email)) = ?', [$email])
            ->exists();

        if ($alreadyReviewed) {
            return redirect()
                ->to(route('businesses.show', $business->slug) . '#reviews')
                ->with('error', 'This email has already submitted a review for this business. You can submit only one review per business.')
                ->withInput($request->only('reviewer_name', 'reviewer_email', 'rating', 'comment'));
        }

        $profanityFilter = app(ProfanityFilter::class);

        if (
            $profanityFilter->containsProfanity($validated['reviewer_name'])
            || $profanityFilter->containsProfanity($validated['comment'])
        ) {
            throw ValidationException::withMessages([
                'comment' => 'Please submit a respectful review without offensive language.',
            ]);
        }

        $requiresApproval = $business->reviews_require_approval ?? false;

        BusinessReview::create([
            'business_id' => $business->id,
            'reviewer_name' => $validated['reviewer_name'],
            'reviewer_email' => $validated['reviewer_email'] ?? null,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => ! $requiresApproval,
        ]);

        $message = $requiresApproval
            ? 'Your review was submitted and will appear after an admin approves it.'
            : 'Your review was added successfully.';

        return redirect()
            ->to(route('businesses.show', $business->slug) . '#reviews')
            ->with('success', $message);
    }
}
