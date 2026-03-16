@extends('layouts.admin')

@section('title', 'Visitor Statistics')
@section('page_title', 'Visitor Statistics')

@section('content')
    <div class="mx-auto max-w-6xl space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-500">Website traffic overview. You can reset all counts to zero below.</p>
                <p class="mt-1 text-xs text-slate-400">Data as of <strong>{{ $dates['as_of'] }}</strong></p>
            </div>
            <form method="POST" action="{{ route('admin.statistics.reset') }}" class="inline" onsubmit="return confirm('{{ __('messages.statistics.reset_confirm') }}');">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 shadow-sm transition hover:bg-red-50 hover:border-red-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('messages.statistics.reset_to_zero') }}
                </button>
            </form>
        </div>

        {{-- Counter cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total visitors</p>
                <p class="mt-2 text-3xl font-bold text-primary">{{ number_format($stats['total_visitors']) }}</p>
                <p class="mt-1 text-xs text-slate-500">All time (as of {{ $dates['today'] }})</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Today</p>
                <p class="mt-2 text-3xl font-bold text-primary">{{ number_format($stats['daily_visitors']) }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $dates['today'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">This week</p>
                <p class="mt-2 text-3xl font-bold text-primary">{{ number_format($stats['weekly_visitors']) }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $dates['week_range'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">This month</p>
                <p class="mt-2 text-3xl font-bold text-primary">{{ number_format($stats['monthly_visitors']) }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $dates['month_label'] }}</p>
            </div>
        </div>

        {{-- Charts row --}}
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Visitors by period</h2>
                <p class="mt-1 text-xs text-slate-500">Today ({{ $dates['today_short'] }}), week ({{ $dates['week_range'] }}), month ({{ $dates['month_short'] }})</p>
                <div class="mt-6 h-64">
                    <canvas id="chart-period" width="400" height="256"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Most visited pages</h2>
                <p class="mt-1 text-xs text-slate-500">Page views (all time)</p>
                <div class="mt-6 h-64">
                    <canvas id="chart-pages" width="400" height="256"></canvas>
                </div>
            </div>
        </div>

        {{-- Most visited pages table --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/80 px-4 py-3 sm:px-6">
                <h2 class="text-base font-semibold text-slate-900">Top pages</h2>
                <p class="mt-0.5 text-xs text-slate-500">Ordered by visit count · All time as of {{ $dates['today'] }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Page</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Path</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Visits</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($mostVisitedPages as $page)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $page['title'] }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 font-mono">{{ $page['path'] }}</td>
                                <td class="px-4 py-3 text-sm text-slate-900 text-right font-semibold">{{ number_format($page['visits']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
    (function() {
        var primary = '{{ optional($settings)->admin_primary_color ?? "#1E3A5F" }}';
        var accent = '{{ optional($settings)->accent_color ?? "#10B981" }}';
        var colors = [accent, primary, '#6366f1'];

        // Visitors by period (bar)
        var ctxPeriod = document.getElementById('chart-period');
        if (ctxPeriod) {
            new Chart(ctxPeriod, {
                type: 'bar',
                data: {
                    labels: @json($periodLabels),
                    datasets: [{
                        label: 'Visitors',
                        data: @json($periodValues),
                        backgroundColor: [colors[0], colors[1], colors[2]],
                        borderColor: [colors[0], colors[1], colors[2]],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { font: { size: 11 } },
                            grid: { color: 'rgba(0,0,0,0.06)' }
                        },
                        x: {
                            ticks: { font: { size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // Most visited pages (horizontal bar)
        var ctxPages = document.getElementById('chart-pages');
        if (ctxPages) {
            var pageLabels = @json(array_column($mostVisitedPages, 'title'));
            var pageData = @json(array_column($mostVisitedPages, 'visits'));
            new Chart(ctxPages, {
                type: 'bar',
                data: {
                    labels: pageLabels,
                    datasets: [{
                        label: 'Visits',
                        data: pageData,
                        backgroundColor: accent,
                        borderColor: accent,
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { font: { size: 10 } },
                            grid: { color: 'rgba(0,0,0,0.06)' }
                        },
                        y: {
                            ticks: { font: { size: 10 }, maxRotation: 0 },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    })();
    </script>
@endsection
