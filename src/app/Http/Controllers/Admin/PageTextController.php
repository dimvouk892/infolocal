<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageText;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageTextController extends Controller
{
    protected array $pages = [
        'home'       => 'Home',
        'about'      => 'About',
        'places'     => 'Places',
        'businesses' => 'Businesses',
        'map'        => 'Map',
        'contact'    => 'Contact',
    ];

    protected array $keys = [
        'hero_title'    => 'Hero title (home)',
        'hero_subtitle' => 'Hero subtitle (home)',
        'title'         => 'Title',
        'subtitle'      => 'Subtitle',
        'intro'         => 'Intro',
    ];

    public function index(Request $request): View
    {
        $texts = PageText::all()
            ->groupBy(fn ($row) => $row->page . '.' . $row->key . '.' . $row->locale);

        return view('admin.page-texts.index', [
            'pages' => $this->pages,
            'keys'  => $this->keys,
            'texts' => $texts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->input('texts', []);

        foreach ($data as $page => $pageKeys) {
            foreach ($pageKeys as $key => $locales) {
                foreach ($locales as $locale => $value) {
                    $value = trim((string) $value);

                    if ($value === '') {
                        PageText::where('page', $page)
                            ->where('key', $key)
                            ->where('locale', $locale)
                            ->delete();
                        continue;
                    }

                    PageText::updateOrCreate(
                        ['page' => $page, 'key' => $key, 'locale' => $locale],
                        ['value' => $value],
                    );
                }
            }
        }

        return redirect()
            ->route('admin.page-texts.index')
            ->with('success', 'Page texts saved.');
    }
}

