@props([
    'name',
    'label',
    'value' => '#000000',
    'default' => '#000000',
])

@php
    $resolved = old($name, $value ?? $default);
    $default = (is_string($default) && preg_match('/^#[0-9A-Fa-f]{6}$/', $default)) ? strtoupper($default) : '#000000';

    if (is_string($resolved) && preg_match('/^#[0-9A-Fa-f]{6}$/', $resolved)) {
        $resolved = strtoupper($resolved);
    } else {
        $resolved = $default;
    }
@endphp

<div
    class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
    x-data="{
        value: @js($resolved),
        syncFromText() {
            const candidate = this.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(candidate)) {
                this.value = candidate.toUpperCase();
            }
        },
        normalize() {
            this.value = /^#[0-9A-Fa-f]{6}$/.test(this.value.trim()) ? this.value.trim().toUpperCase() : @js($default);
        }
    }"
>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <label class="block text-sm font-semibold text-slate-800">{{ $label }}</label>
            <p class="mt-1 text-xs text-slate-500">Pick a color or enter a HEX value manually.</p>
        </div>

        <div class="h-10 w-10 rounded-xl border border-slate-300 shadow-inner" :style="`background-color: ${value}`"></div>
    </div>

    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">
        <input
            type="color"
            x-model="value"
            class="h-11 w-16 cursor-pointer rounded-xl border border-slate-300 bg-white p-1"
        >

        <input
            type="text"
            name="{{ $name }}"
            x-model="value"
            @input="syncFromText()"
            @blur="normalize()"
            class="block w-full rounded-xl border-slate-300 bg-white text-sm font-mono shadow-sm focus:border-accent focus:ring-accent"
            placeholder="{{ $default }}"
        >
    </div>
</div>
