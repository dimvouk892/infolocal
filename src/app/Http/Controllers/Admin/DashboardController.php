<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Setting;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'businesses' => Business::count(),
            'businesses_published' => Business::published()->count(),
            'users' => User::count(),
        ];

        $visitorStats = $this->visitorStatsForChart();

        $recentUsers = User::orderByDesc('created_at')->take(10)->get();

        return view('admin.dashboard', compact('stats', 'visitorStats', 'recentUsers'));
    }

    private function visitorStatsForChart(): array
    {
        $stored = Setting::get('visitor_statistics');
        $data = is_string($stored) ? json_decode($stored, true) : $stored;

        if (! is_array($data) || empty($data)) {
            return [
                'labels' => ['Daily', 'Weekly', 'Monthly'],
                'values' => [342, 1890, 7200],
            ];
        }

        return [
            'labels' => ['Daily', 'Weekly', 'Monthly'],
            'values' => [
                (int) ($data['daily_visitors'] ?? 0),
                (int) ($data['weekly_visitors'] ?? 0),
                (int) ($data['monthly_visitors'] ?? 0),
            ],
        ];
    }
}
