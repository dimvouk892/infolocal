<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaceCategory;
use App\Support\MapPin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlaceCategoryController extends Controller
{
    public function index(): View
    {
        $categories = PlaceCategory::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.place-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.place-categories.create', [
            'pinOptions' => MapPin::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'map_pin_icon' => ['required', 'string', Rule::in(array_keys(MapPin::options()))],
            'map_pin_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['map_pin_color'] = MapPin::normalizeColor($validated['map_pin_color']);
        PlaceCategory::create($validated);
        return redirect()->route('admin.place-categories.index')->with('success', __('Place category created.'));
    }

    public function edit(PlaceCategory $place_category): View
    {
        return view('admin.place-categories.edit', [
            'placeCategory' => $place_category,
            'pinOptions' => MapPin::options(),
        ]);
    }

    public function update(Request $request, PlaceCategory $place_category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'map_pin_icon' => ['required', 'string', Rule::in(array_keys(MapPin::options()))],
            'map_pin_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['map_pin_color'] = MapPin::normalizeColor($validated['map_pin_color']);
        $place_category->update($validated);
        return redirect()->route('admin.place-categories.index')->with('success', __('Place category updated.'));
    }

    public function destroy(PlaceCategory $place_category): RedirectResponse
    {
        $place_category->delete();
        return redirect()->route('admin.place-categories.index')->with('success', __('Place category deleted.'));
    }
}
