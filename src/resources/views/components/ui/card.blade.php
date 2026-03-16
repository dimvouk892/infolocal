@props([
    'padding' => true,
    'hover' => false,
])

@php
    $classes = 'ui-card rounded-2xl border shadow-sm overflow-hidden';
    if ($hover) {
        $classes .= ' transition-all duration-300 hover:shadow-lg';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($padding)
        <div class="p-5 sm:p-6">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>
