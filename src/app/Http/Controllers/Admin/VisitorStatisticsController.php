<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitorStatisticsController extends Controller
{
    private const DEFAULT_STATS = [
        'total_visitors' => 12450,
        'daily_visitors' => 342,
        'weekly_visitors' => 1890,
        'monthly_visitors' => 7200,
    ];

    private const DEFAULT_PAGES = [
        ['path' => '/', 'title' => 'Home', 'visits' => 5200],
        ['path' => '/places', 'title' => 'Places to Visit', 'visits' => 3100],
        ['path' => '/businesses', 'title' => 'Local Businesses', 'visits' => 2450],
        ['path' => '/about', 'title' => 'About', 'visits' => 980],
        ['path' => '/contact', 'title' => 'Contact', 'visits' => 720],
        ['path' => '/businesses-on-map', 'title' => 'On the Map', 'visits' => 650],
    ];

    /**
     * Display visitor statistics. Uses stored values if set (e.g. after reset), otherwise default demo data.
     */
    public function index(): View
    {
        $stored = Setting::get('visitor_statistics');
        $data = is_string($stored) ? json_decode($stored, true) : $stored;

        if (! is_array($data) || empty($data)) {
            $stats = self::DEFAULT_STATS;
            $mostVisitedPages = self::DEFAULT_PAGES;
        } else {
            $stats = [
                'total_visitors' => (int) ($data['total_visitors'] ?? 0),
                'daily_visitors' => (int) ($data['daily_visitors'] ?? 0),
                'weekly_visitors' => (int) ($data['weekly_visitors'] ?? 0),
                'monthly_visitors' => (int) ($data['monthly_visitors'] ?? 0),
            ];
            $pages = $data['most_visited_pages'] ?? self::DEFAULT_PAGES;
            $mostVisitedPages = is_array($pages) ? $pages : self::DEFAULT_PAGES;
            foreach ($mostVisitedPages as $i => $p) {
                if (! isset($mostVisitedPages[$i]['visits'])) {
                    $mostVisitedPages[$i]['visits'] = 0;
                }
            }
        }

        $periodLabels = ['Daily', 'Weekly', 'Monthly'];
        $periodValues = [
            $stats['daily_visitors'],
            $stats['weekly_visitors'],
            $stats['monthly_visitors'],
        ];

        $now = Carbon::now();
        $weekStart = $now->copy()->startOfWeek();
        $monthStart = $now->copy()->startOfMonth();

        $dates = [
            'today' => $now->format('d M Y'),
            'today_short' => $now->format('d/m/Y'),
            'week_range' => $weekStart->format('d M') . ' – ' . $now->format('d M Y'),
            'month_label' => $now->format('F Y'),
            'month_short' => $now->format('M Y'),
            'as_of' => $now->format('d M Y, H:i'),
        ];

        return view('admin.statistics.index', [
            'stats' => $stats,
            'mostVisitedPages' => $mostVisitedPages,
            'periodLabels' => $periodLabels,
            'periodValues' => $periodValues,
            'dates' => $dates,
        ]);
    }

    /**
     * Reset all visitor statistics to zero. Stores zeros in settings.
     */
    public function reset(Request $request): RedirectResponse
    {
        $zeroPages = array_map(function ($p) {
            return ['path' => $p['path'], 'title' => $p['title'], 'visits' => 0];
        }, self::DEFAULT_PAGES);

        Setting::set('visitor_statistics', [
            'total_visitors' => 0,
            'daily_visitors' => 0,
            'weekly_visitors' => 0,
            'monthly_visitors' => 0,
            'most_visited_pages' => $zeroPages,
        ]);

        return redirect()->route('admin.statistics.index')->with('success', __('messages.statistics.reset_success'));
    }
}
