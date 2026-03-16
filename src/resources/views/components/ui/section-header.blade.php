@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4']) }}>
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-primary">
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="mt-1 text-sm text-stone-600">
                {{ $subtitle }}
            </p>
        @endif
    </div>
    @isset($action)
        <div class="shrink-0">
            {{ $action }}
        </div>
    @endisset
</div>
