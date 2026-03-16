<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VillageController extends Controller
{
    public function index(): View
    {
        $villages = Village::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.villages.index', compact('villages'));
    }

    public function create(): View
    {
        return view('admin.villages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_el' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        Village::create($validated);
        return redirect()->route('admin.villages.index')->with('success', __('Village created.'));
    }

    public function edit(Village $village): View
    {
        return view('admin.villages.edit', compact('village'));
    }

    public function update(Request $request, Village $village): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_el' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $village->update($validated);
        return redirect()->route('admin.villages.index')->with('success', __('Village updated.'));
    }

    public function destroy(Village $village): RedirectResponse
    {
        $village->delete();
        return redirect()->route('admin.villages.index')->with('success', __('Village deleted.'));
    }
}
