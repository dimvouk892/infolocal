@extends('layouts.admin')

@section('title', 'Add Place')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">Add place</h1>
    <form method="POST" action="{{ route('admin.places.store') }}" enctype="multipart/form-data" class="mt-6 max-w-2xl space-y-6">
        @csrf
        <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
            <div>
                <label for="featured_image" class="block text-sm font-medium text-slate-700">Featured image (JPG, PNG, WEBP, max 2MB)</label>
                <p class="mt-0.5 text-xs text-slate-500">Optional. Leave empty if you don't have an image yet.</p>
                <input id="featured_image" type="file" name="featured_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/jpg,image/png,image/webp" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                @error('featured_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="gallery" class="block text-sm font-medium text-slate-700">Gallery images</label>
                <p class="mt-0.5 text-xs text-slate-500">JPG, PNG, WEBP, max 2MB each. Optional.</p>
                <input id="gallery" type="file" name="gallery[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/jpg,image/png,image/webp" multiple class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                @error('gallery.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="title" class="block text-sm font-medium text-slate-700">Title (English) <span class="text-red-500">*</span></label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="e.g. Melidoni Cave">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="title_el" class="block text-sm font-medium text-slate-700">Τίτλος (Ελληνικά)</label>
                <input id="title_el" type="text" name="title_el" value="{{ old('title_el') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Προαιρετικό">
            </div>
            <div>
                <label for="place_category_id" class="block text-sm font-medium text-slate-700">Category</label>
                <select id="place_category_id" name="place_category_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                    <option value="">—</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('place_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            @if(isset($villages) && $villages->isNotEmpty())
            <div>
                <label for="village_id" class="block text-sm font-medium text-slate-700">Village</label>
                <select id="village_id" name="village_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                    <option value="">—</option>
                    @foreach($villages as $v)
                        <option value="{{ $v->id }}" {{ old('village_id') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label for="short_description" class="block text-sm font-medium text-slate-700">Short description (English)</label>
                <textarea id="short_description" name="short_description" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Brief intro for cards and meta">{{ old('short_description') }}</textarea>
                @error('short_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="short_description_el" class="block text-sm font-medium text-slate-700">Σύντομη περιγραφή (Ελληνικά)</label>
                <textarea id="short_description_el" name="short_description_el" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Προαιρετικό">{{ old('short_description_el') }}</textarea>
            </div>
            <div>
                <label for="full_content" class="block text-sm font-medium text-slate-700">Full description (English)</label>
                <textarea id="full_content" name="full_content" rows="6" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Full text shown on the place page">{{ old('full_content') }}</textarea>
                @error('full_content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="full_content_el" class="block text-sm font-medium text-slate-700">Πλήρης περιγραφή (Ελληνικά)</label>
                <textarea id="full_content_el" name="full_content_el" rows="6" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Προαιρετικό">{{ old('full_content_el') }}</textarea>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                <input id="address" type="text" name="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Street, town, region">
                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="video_url" class="block text-sm font-medium text-slate-700">Video URL</label>
                <p class="mt-0.5 text-xs text-slate-500">YouTube or Vimeo link. Optional.</p>
                <input id="video_url" type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                @error('video_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Map location (coordinates)</label>
                <p class="mt-0.5 text-xs text-slate-500">Optional. In Google Maps, right‑click the place → click the coordinates to copy.</p>
                <div class="mt-1 grid grid-cols-2 gap-3">
                    <div>
                        <label for="map_lat" class="sr-only">Latitude</label>
                        <input id="map_lat" type="text" name="map_lat" value="{{ old('map_lat') }}" placeholder="35.31" inputmode="decimal" class="block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        <span class="text-xs text-slate-500">Latitude</span>
                    </div>
                    <div>
                        <label for="map_lng" class="sr-only">Longitude</label>
                        <input id="map_lng" type="text" name="map_lng" value="{{ old('map_lng') }}" placeholder="25.08" inputmode="decimal" class="block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        <span class="text-xs text-slate-500">Longitude</span>
                    </div>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                </div>
            </div>
            <div>
                <label for="website" class="block text-sm font-medium text-slate-700">Website</label>
                <input id="website" type="url" name="website" value="{{ old('website') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="https://...">
                @error('website')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort order</label>
                    <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                    <span class="ml-2 text-sm text-slate-700">Featured (show badge on listing)</span>
                </label>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Create</button>
            <a href="{{ route('admin.places.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
@endsection
