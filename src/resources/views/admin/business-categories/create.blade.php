@extends('layouts.admin')

@section('title', 'Add Business Category')

@section('content')
    <div class="space-y-2">
        <h1 class="text-2xl font-semibold text-slate-900">Add Business Category</h1>
        <p class="text-sm text-slate-500">Choose the pin icon and color that this category will use on the map.</p>
    </div>
    <form method="POST" action="{{ route('admin.business-categories.store') }}" class="mt-6 max-w-2xl space-y-6">
        @csrf
        <x-ui.card>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Name (EN)</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="name_el" class="block text-sm font-medium text-slate-700">Name (ΕΛ)</label>
                    <input id="name_el" type="text" name="name_el" value="{{ old('name_el') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Ξενοδοχεία">
                    @error('name_el')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2 mt-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort order</label>
                    <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                </div>
            </div>
        </x-ui.card>

        <div class="grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
            <x-ui.card>
                <div class="space-y-4">
                    <div>
                        <label for="map_pin_icon" class="block text-sm font-medium text-slate-700">Map pin icon</label>
                        <select id="map_pin_icon" name="map_pin_icon" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                            @foreach($pinOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('map_pin_icon', 'map-pin') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('map_pin_icon')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <x-ui.color-field
                            name="map_pin_color"
                            label="Map pin color"
                            :value="old('map_pin_color', '#10B981')"
                            default="#10B981"
                        />
                        @error('map_pin_color')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-sm font-semibold text-primary">Map behavior</h2>
                <p class="mt-2 text-sm text-slate-500">All businesses in this category will use the selected pin on the public map page.</p>
                <div class="mt-4 rounded-xl bg-secondary/60 p-4 text-sm text-slate-600">
                    Only admins can change category pin styles.
                </div>
            </x-ui.card>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Create</button>
            <a href="{{ route('admin.business-categories.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
@endsection
