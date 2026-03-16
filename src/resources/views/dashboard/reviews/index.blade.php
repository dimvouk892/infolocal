@extends('layouts.dashboard')

@section('title', __('messages.reviews.title'))

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">{{ __('messages.dashboard.label') }}</p>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('messages.reviews.for_your_businesses') }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ __('messages.reviews.approve_or_hide') }}</p>
            </div>

            <form method="GET" action="{{ route('dashboard.business.reviews.index') }}" class="flex flex-wrap items-center gap-3">
                <select name="status" class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="" {{ $status === '' ? 'selected' : '' }}>{{ __('messages.reviews.all_reviews') }}</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>{{ __('messages.reviews.visible') }}</option>
                    <option value="hidden" {{ $status === 'hidden' ? 'selected' : '' }}>{{ __('messages.reviews.pending_hidden') }}</option>
                </select>
                <button type="submit" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    {{ __('messages.reviews.filter') }}
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.reviews.business') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.reviews.visitor') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.reviews.rating') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.reviews.comment') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.subscription.status') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.reviews.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($reviews as $review)
                            <tr>
                                <td class="px-4 py-4 text-sm text-slate-700">{{ $review->business?->name ?? '—' }}</td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    <div class="font-medium text-slate-900">{{ $review->reviewer_name }}</div>
                                    @if($review->reviewer_email)
                                        <div class="mt-1 text-xs text-slate-500">{{ $review->reviewer_email }}</div>
                                    @endif
                                    <div class="mt-1 text-xs text-slate-400">{{ optional($review->created_at)->format('d M Y H:i') }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    <div class="text-base leading-none text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= (int) $review->rating ? 'opacity-100' : 'opacity-25' }}">★</span>
                                        @endfor
                                    </div>
                                    <div class="mt-1 text-xs font-semibold text-slate-600">{{ (int) $review->rating }}/5</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600">
                                    <p class="max-w-md whitespace-pre-line leading-6">{{ \Illuminate\Support\Str::limit($review->comment, 160) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $review->is_approved ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $review->is_approved ? __('messages.reviews.visible') : __('messages.reviews.pending_approval') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('dashboard.business.reviews.update', $review) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                                            <button type="submit" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                                {{ $review->is_approved ? __('messages.reviews.hide') : __('messages.reviews.approve') }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.business.reviews.destroy', $review) }}" class="inline" onsubmit="return confirm('{{ __('messages.reviews.delete_review_confirm') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">
                                                {{ __('messages.reviews.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">{{ __('messages.reviews.no_reviews_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($reviews, 'links'))
            <div>{{ $reviews->links() }}</div>
        @endif
    </div>
@endsection
