@props([
    'variant' => 'accent',
])

@php
    $variants = [
        'accent' => 'bg-accent text-white',
        'primary' => 'bg-primary text-white',
        'secondary' => 'bg-secondary text-primary',
        'outline' => 'border border-primary text-primary',
    ];
    $classes = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wide ' . ($variants[$variant] ?? $variants['accent']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
