@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-70 disabled:pointer-events-none';
    $variants = [
        'primary' => 'ui-button-primary focus:ring-accent',
        'secondary' => 'bg-secondary text-primary hover:bg-secondary-hover focus:ring-primary',
        'outline' => 'border-2 border-primary text-primary hover:bg-primary hover:text-white focus:ring-primary',
        'ghost' => 'text-primary hover:bg-secondary focus:ring-primary',
        'white' => 'bg-white text-primary hover:bg-neutral focus:ring-primary',
    ];
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
