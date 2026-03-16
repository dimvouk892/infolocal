@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <p class="text-sm text-slate-600 mb-8">{{ optional($settings)->site_title ?? __('messages.brand.name') }} – manage businesses and content.</p>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.card>
            <p class="text-xs font-semibold text-slate-500 uppercase">Businesses (total)</p>
            <p class="mt-1 text-2xl font-semibold text-primary">{{ $stats['businesses'] }}</p>
        </x-ui.card>
        <x-ui.card>
            <p class="text-xs font-semibold text-slate-500 uppercase">Businesses (published)</p>
            <p class="mt-1 text-2xl font-semibold text-accent">{{ $stats['businesses_published'] }}</p>
        </x-ui.card>
        <x-ui.card>
            <p class="text-xs font-semibold text-slate-500 uppercase">Users</p>
            <p class="mt-1 text-2xl font-semibold text-primary">{{ $stats['users'] }}</p>
        </x-ui.card>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-ui.card class="overflow-hidden">
            <div class="border-b border-slate-100 px-4 py-3 sm:px-6">
                <h2 class="text-base font-semibold text-slate-900">Recent users</h2>
                <p class="mt-0.5 text-xs text-slate-500">Latest registered users. <a href="{{ route('admin.users.index') }}" class="text-accent hover:underline">View all users →</a></p>
            </div>
            <div class="overflow-x-auto">
                @if($recentUsers->isEmpty())
                    <p class="p-4 text-sm text-slate-500">No users yet.</p>
                @else
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600 uppercase">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600 uppercase">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600 uppercase">Role</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600 uppercase">Registered</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($recentUsers as $u)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-2 text-sm font-medium text-slate-900">{{ $u->name }}</td>
                                    <td class="px-4 py-2 text-sm text-slate-600">{{ $u->email }}</td>
                                    <td class="px-4 py-2"><span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $u->role === 'admin' ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-600' }}">{{ $u->role }}</span></td>
                                    <td class="px-4 py-2 text-sm text-slate-500 text-right">{{ $u->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </x-ui.card>
        <x-ui.card class="overflow-hidden">
            <div class="border-b border-slate-100 px-4 py-3 sm:px-6">
                <h2 class="text-base font-semibold text-slate-900">Visitor statistics</h2>
                <p class="mt-0.5 text-xs text-slate-500">Daily, weekly and monthly visitors. <a href="{{ route('admin.statistics.index') }}" class="text-accent hover:underline">View full statistics →</a></p>
            </div>
            <div class="p-4 sm:p-6">
                <div class="h-64">
                    <canvas id="dashboard-visitor-chart" width="400" height="256"></canvas>
                </div>
            </div>
        </x-ui.card>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
    (function() {
        var ctx = document.getElementById('dashboard-visitor-chart');
        if (!ctx) return;
        var primary = '{{ optional($settings)->admin_primary_color ?? "#1E3A5F" }}';
        var accent = '{{ optional($settings)->accent_color ?? "#10B981" }}';
        var colors = [accent, primary, '#6366f1'];
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($visitorStats['labels']),
                datasets: [{
                    label: 'Visitors',
                    data: @json($visitorStats['values']),
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.06)' } },
                    x: { ticks: { font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    })();
    </script>
@endsection
