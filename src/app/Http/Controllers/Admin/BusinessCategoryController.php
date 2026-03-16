<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Support\MapPin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BusinessCategoryController extends Controller
{
    public function index(): View
    {
        $categories = BusinessCategory::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.business-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.business-categories.create', [
            'pinOptions' => MapPin::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_el' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'map_pin_icon' => ['required', 'string', Rule::in(array_keys(MapPin::options()))],
            'map_pin_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['map_pin_color'] = MapPin::normalizeColor($validated['map_pin_color']);
        BusinessCategory::create($validated);
        return redirect()->route('admin.business-categories.index')->with('success', __('Category created.'));
    }

    public function edit(BusinessCategory $businessCategory): View
    {
        return view('admin.business-categories.edit', [
            'category' => $businessCategory,
            'pinOptions' => MapPin::options(),
        ]);
    }

    public function update(Request $request, BusinessCategory $businessCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_el' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'map_pin_icon' => ['required', 'string', Rule::in(array_keys(MapPin::options()))],
            'map_pin_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['map_pin_color'] = MapPin::normalizeColor($validated['map_pin_color']);
        $businessCategory->update($validated);
        return redirect()->route('admin.business-categories.index')->with('success', __('Category updated.'));
    }

    public function destroy(BusinessCategory $businessCategory): RedirectResponse
    {
        $businessCategory->delete();
        return redirect()->route('admin.business-categories.index')->with('success', __('Category deleted.'));
    }
}
