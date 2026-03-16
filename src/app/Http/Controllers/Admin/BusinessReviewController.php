<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessReviewController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $reviews = BusinessReview::query()
            ->with('business:id,name,slug')
            ->when($status === 'approved', fn ($query) => $query->where('is_approved', true))
            ->when($status === 'hidden', fn ($query) => $query->where('is_approved', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.business-reviews.index', [
            'reviews' => $reviews,
            'status' => $status,
        ]);
    }

    public function update(Request $request, BusinessReview $businessReview): RedirectResponse
    {
        $validated = $request->validate([
            'is_approved' => ['required', 'boolean'],
        ]);

        $businessReview->update([
            'is_approved' => (bool) $validated['is_approved'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Review visibility updated.');
    }

    public function destroy(BusinessReview $businessReview): RedirectResponse
    {
        $businessReview->delete();

        return redirect()
            ->back()
            ->with('success', 'Review deleted successfully.');
    }
}
