@props(['src' => null, 'alt' => 'Image', 'class' => ''])
@php
    $src = trim((string) ($src ?? ''));
    $placeholderUrl = asset('images/placeholder.svg');
    if ($src === '') {
        $url = $placeholderUrl;
    } elseif (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
        $url = $src;
    } else {
        $path = $src;
        $path = preg_replace('#^/storage/#', '', $path);
        $path = preg_replace('#^storage/#', '', $path);
        $path = ltrim($path, '/');
        $url = $path !== '' ? '/storage/' . $path : $placeholderUrl;
    }
@endphp
<img src="{{ $url }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => $class]) }} loading="lazy" onerror="this.onerror=null;this.src='{{ $placeholderUrl }}';" />
