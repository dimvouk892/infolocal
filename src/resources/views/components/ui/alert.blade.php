@props([
    'variant' => 'info',
])

@php
    $variants = [
        'success' => 'ui-alert-success',
        'error' => 'ui-alert-error',
        'warning' => 'ui-alert-warning',
        'info' => 'ui-alert-info',
    ];
    $classes = 'rounded-lg border p-4 ' . ($variants[$variant] ?? $variants['info']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} role="alert">
    {{ $slot }}
</div>
