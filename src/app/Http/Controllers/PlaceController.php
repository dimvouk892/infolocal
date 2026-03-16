<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PlaceController extends Controller
{
    public function index(Request $request): View
    {
        $categories = PlaceCategory::orderBy('sort_order')->orderBy('name')->get();
        $villages = \Illuminate\Support\Facades\Schema::hasTable('villages')
            ? Village::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'name_el', 'slug'])
            : collect();
        $currentVillage = $request->query('village', '');

        $query = Place::published()
            ->with(['category', 'villages'])
            ->when(
                $request->query('category'),
                fn ($q) => $q->whereHas('category', fn ($q2) => $q2->where('slug', $request->query('category')))
            )
            ->when($currentVillage, fn ($q) => $q->whereHas('villages', fn ($q2) => $q2->where('villages.slug', $currentVillage)));

        if (Schema::hasColumn('places', 'featured')) {
            $query->orderByDesc('featured');
        }
        $places = $query->ordered()->get();

        return view('places.index', [
            'places' => $places,
            'categories' => $categories,
            'currentCategory' => $request->query('category', ''),
            'villages' => $villages,
            'currentVillage' => $currentVillage,
        ]);
    }

    public function show(string $slug): View
    {
        $place = Place::published()
            ->where('slug', $slug)
            ->with('category')
            ->firstOrFail();

        return view('places.show', compact('place'));
    }
}
