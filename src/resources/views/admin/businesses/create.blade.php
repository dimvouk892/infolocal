@extends('layouts.admin')

@section('title', 'Add Business')

@section('content')
    <h1 class="text-2xl font-semibold text-slate-900">Add Business</h1>
    <form method="POST" action="{{ route('admin.businesses.store') }}" enctype="multipart/form-data" class="mt-6 max-w-2xl space-y-6">
        @csrf
        <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
            <div>
                <label for="logo" class="block text-sm font-medium text-slate-700">Logo (JPG/PNG/WEBP, max 2MB)</label>
                <p class="mt-0.5 text-xs text-slate-500">Optional. Shown on business cards.</p>
                <input id="logo" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/jpg,image/png,image/webp" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                @error('logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="featured_image" class="block text-sm font-medium text-slate-700">Featured image (JPG/JPEG, max 2MB) <span class="text-red-500">*</span></label>
                <input id="featured_image" type="file" name="featured_image" accept=".jpg,.jpeg,image/jpeg,image/jpg" required class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                @error('featured_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="gallery" class="block text-sm font-medium text-slate-700">More images (gallery)</label>
                <p class="mt-0.5 text-xs text-slate-500">JPG/JPEG, max 2MB each. Optional.</p>
                <input id="gallery" type="file" name="gallery[]" accept=".jpg,.jpeg,image/jpeg,image/jpg" multiple class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                @error('gallery.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Name (English)</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="name_el" class="block text-sm font-medium text-slate-700">Όνομα (Ελληνικά)</label>
                <input id="name_el" type="text" name="name_el" value="{{ old('name_el') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Προαιρετικό">
            </div>
            <div>
                <label for="business_category_id" class="block text-sm font-medium text-slate-700">Category</label>
                <select id="business_category_id" name="business_category_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                    <option value="">—</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('business_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                <label for="description" class="block text-sm font-medium text-slate-700">Description (English)</label>
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">{{ old('description') }}</textarea>
            </div>
            <div>
                <label for="description_el" class="block text-sm font-medium text-slate-700">Περιγραφή (Ελληνικά)</label>
                <textarea id="description_el" name="description_el" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm" placeholder="Προαιρετικό">{{ old('description_el') }}</textarea>
            </div>
            <div>
                <label for="video_url" class="block text-sm font-medium text-slate-700">Video URL</label>
                <p class="mt-0.5 text-xs text-slate-500">YouTube or Vimeo link. Optional.</p>
                <input id="video_url" type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                @error('video_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                <input id="address" type="text" name="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('messages.businesses.map_location_label') }}</label>
                <p class="mt-0.5 text-xs text-slate-500">{{ __('messages.businesses.map_location_help') }}</p>
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
                @error('map_lat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                @error('map_lng')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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
                <input id="website" type="url" name="website" value="{{ old('website') }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
            </div>
            <div class="border-t border-slate-200 pt-4 mt-4">
                <p class="text-sm font-medium text-slate-700 mb-3">Social links</p>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div>
                        <label for="facebook" class="block text-xs text-slate-500">Facebook</label>
                        <input id="facebook" type="url" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/..." class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                    </div>
                    <div>
                        <label for="instagram" class="block text-xs text-slate-500">Instagram</label>
                        <input id="instagram" type="url" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/..." class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                    </div>
                    <div>
                        <label for="tripadvisor" class="block text-xs text-slate-500">Tripadvisor</label>
                        <input id="tripadvisor" type="url" name="tripadvisor" value="{{ old('tripadvisor') }}" placeholder="https://tripadvisor.com/..." class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                    </div>
                </div>
                @error('facebook')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                @error('instagram')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                @error('tripadvisor')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div>
                    <label for="owner_id" class="block text-sm font-medium text-slate-700">Owner (user)</label>
                    <select id="owner_id" name="owner_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('owner_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                    <span class="ml-2 text-sm text-slate-700">Featured</span>
                </label>
            </div>
            <div>
                <label class="inline-flex items-start">
                    <input type="checkbox" name="reviews_enabled" value="1" {{ old('reviews_enabled', '1') ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-emerald-600">
                    <span class="ml-2">
                        <span class="block text-sm text-slate-700">Allow visitor reviews</span>
                        <span class="block text-xs text-slate-500">If enabled, visitors can submit ratings and comments on this business page.</span>
                    </span>
                </label>
            </div>
            <div>
                <label class="inline-flex items-start">
                    <input type="checkbox" name="reviews_require_approval" value="1" {{ old('reviews_require_approval') ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-emerald-600">
                    <span class="ml-2">
                        <span class="block text-sm text-slate-700">Reviews require admin approval</span>
                        <span class="block text-xs text-slate-500">If checked, new reviews stay hidden until an admin approves them in Admin → Reviews.</span>
                    </span>
                </label>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Create</button>
            <a href="{{ route('admin.businesses.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
@endsection
