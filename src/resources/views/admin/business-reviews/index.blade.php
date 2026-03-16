@extends('layouts.admin')

@section('title', 'Business Reviews')
@section('page_title', 'Business Reviews')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Visitor Comments</p>
                <h1 class="mt-1 text-2xl font-semibold text-primary">Business Reviews</h1>
                <p class="mt-2 text-sm text-slate-500">Manage visitor ratings and comments for all business pages.</p>
            </div>

            <form method="GET" action="{{ route('admin.business-reviews.index') }}" class="flex flex-wrap items-center gap-3">
                <select name="status" class="rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                    <option value="" {{ $status === '' ? 'selected' : '' }}>All reviews</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Visible</option>
                    <option value="hidden" {{ $status === 'hidden' ? 'selected' : '' }}>Hidden</option>
                </select>
                <x-ui.button type="submit" variant="secondary">Filter</x-ui.button>
            </form>
        </div>

        <x-ui.card :padding="false">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Business</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Visitor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Rating</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Comment</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($reviews as $review)
                            <tr>
                                <td class="px-4 py-4 text-sm">
                                    @if($review->business)
                                        <a href="{{ route('businesses.show', $review->business->slug) }}" target="_blank" rel="noopener" class="font-semibold text-primary hover:text-accent">
                                            {{ $review->business->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">Deleted business</span>
                                    @endif
                                </td>
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
                                    <p class="max-w-xl whitespace-pre-line leading-6">{{ \Illuminate\Support\Str::limit($review->comment, 180) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $review->is_approved ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $review->is_approved ? 'Visible' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.business-reviews.update', $review) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                                            <x-ui.button type="submit" variant="secondary" size="sm">
                                                {{ $review->is_approved ? 'Hide' : 'Show' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.business-reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" size="sm" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                                                Delete
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">No reviews found yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        @if(method_exists($reviews, 'links'))
            <div>
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
