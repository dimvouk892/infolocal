@props(['tour'])

<article class="group rounded-2xl overflow-hidden bg-white shadow-sm border border-slate-100 flex flex-col">
    <div class="relative h-48 overflow-hidden">
        <img src="{{ $tour['image'] }}"
             alt="{{ $tour['name'] }}"
             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        <div class="absolute top-3 left-3 inline-flex items-center rounded-full bg-emerald-600/90 px-3 py-1 text-xs font-semibold text-white shadow">
            {{ $tour['duration'] }}
        </div>
    </div>
    <div class="flex-1 p-5 space-y-3 flex flex-col">
        <h3 class="text-base font-semibold text-slate-900 group-hover:text-emerald-700">
            {{ $tour['name'] }}
        </h3>
        <p class="text-sm text-slate-600 line-clamp-3">
            {{ $tour['highlight'] }}
        </p>
        <div class="mt-auto pt-2 flex items-center justify-between">
            <span class="text-xs text-slate-500">
                {{ __('messages.tours.info_only') }}
            </span>
        </div>
    </div>
</article>

