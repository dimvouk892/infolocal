@props([
    'label' => null,
    'error' => null,
    'id' => null,
])

@php
    $id = $id ?? 'input-' . uniqid();
    $base = 'block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-accent focus:ring-accent';
    if ($error) {
        $base .= ' border-red-500 focus:border-red-500 focus:ring-red-500';
    }
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-semibold text-slate-700 mb-1">
            {{ $label }}
        </label>
    @endif
    <input
        id="{{ $id }}"
        {{ $attributes->except('class')->merge(['class' => $base]) }}
    >
    @if($error)
        <p class="mt-1 text-xs text-red-600">{{ $error }}</p>
    @endif
</div>
