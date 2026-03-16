@extends('layouts.admin')

@section('title', 'Edit Business')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">Admin / Businesses</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Edit business</h1>
                <p class="mt-1 text-sm text-slate-500">Update media, content, contact details, and publishing options.</p>
            </div>

            <a href="{{ route('admin.businesses.index') }}"
               class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                Back to businesses
            </a>
        </div>

        <form method="POST"
              action="{{ route('admin.businesses.update', $business) }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
                <div class="space-y-6">

                    {{-- MEDIA --}}
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                            <h2 class="text-lg font-semibold text-slate-900">Media</h2>
                            <p class="mt-1 text-sm text-slate-500">Logo, featured image and gallery. JPG/PNG/WEBP up to 2MB. Leave empty to keep current.</p>
                        </div>

                        <div class="space-y-8 p-6">
                            <div>
                                <label for="logo" class="block text-sm font-semibold text-slate-800">Logo</label>
                                <p class="mt-1 text-xs text-slate-500">Official business logo (shown on cards). Leave empty to keep current.</p>
                                <div class="mt-4 grid gap-4 lg:grid-cols-[180px_minmax(0,1fr)]">
                                    <div class="flex aspect-square max-h-[180px] w-full items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                        <x-image-or-placeholder id="logo-preview" :src="$business->logo" :alt="$business->name" class="max-h-full w-auto max-w-full object-contain" />
                                    </div>
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 space-y-3 p-5">
                                        <input type="hidden" name="logo_path" id="logo_path" value="">
                                        <input id="logo" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/jpg,image/png,image/webp"
                                               class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-700">
                                        <p class="text-xs text-slate-500">or</p>
                                        <a href="#" id="browse-logo-media" target="_blank" rel="noopener"
                                           class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Browse Media
                                        </a>
                                        @error('logo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="featured_image" class="block text-sm font-semibold text-slate-800">Featured image</label>
                                <p class="mt-1 text-xs text-slate-500">Leave empty to keep current image.</p>

                                <div class="mt-4 grid gap-4 lg:grid-cols-[220px_minmax(0,1fr)]">
                                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                        <x-image-or-placeholder id="featured-preview" :src="$business->featured_image" :alt="$business->name" class="h-52 w-full object-cover" />
                                    </div>
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-5 space-y-3">
                                        <input type="hidden" name="featured_image_path" id="featured_image_path" value="">
                                        <input id="featured_image" type="file" name="featured_image" accept=".jpg,.jpeg,image/jpeg,image/jpg"
                                               class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-700">
                                        <p class="text-xs text-slate-500">or</p>
                                        <a href="#" id="browse-featured-media" target="_blank" rel="noopener"
                                           class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Browse Media
                                        </a>
                                        @error('featured_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800">Current gallery</label>
                                <p class="mt-1 text-xs text-slate-500">Drag to reorder. Check "Remove image" to delete.</p>

                                <ul id="gallery-sortable" class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                    @foreach($business->gallery ?? [] as $idx => $path)
                                        <li class="gallery-sort-item group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                            <div class="relative">
                                                <x-image-or-placeholder :src="$path" alt="Gallery" class="h-40 w-full object-cover" />
                                                <div class="absolute left-3 top-3">
                                                    <span class="gallery-drag-handle inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-600 shadow cursor-grab active:cursor-grabbing" title="Drag to reorder">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="space-y-3 p-4">
                                                <p class="truncate text-sm font-medium text-slate-700">{{ basename($path) }}</p>
                                                <label class="inline-flex items-center text-sm text-slate-600">
                                                    <input type="checkbox" name="gallery_remove[]" value="{{ $path }}" class="gallery-remove rounded border-slate-300 text-red-600 focus:ring-red-500" data-keep-id="bkeep-{{ $idx }}">
                                                    <span class="ml-2">Remove image</span>
                                                </label>
                                                <input type="hidden" name="gallery_keep[]" value="{{ $path }}" id="bkeep-{{ $idx }}" class="gallery-keep-input">
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div>
                                <label for="gallery" class="block text-sm font-semibold text-slate-800">Add gallery images</label>
                                <p class="mt-1 text-xs text-slate-500">You can select multiple images. JPG/JPEG, max 2MB each.</p>
                                <div class="mt-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-5">
                                    <input id="gallery" type="file" name="gallery[]" multiple accept=".jpg,.jpeg,image/jpeg,image/jpg"
                                           class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800">
                                    <div id="gallery-new-preview" class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3"></div>
                                </div>
                                @error('gallery.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    {{-- BASIC INFO --}}
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                            <h2 class="text-lg font-semibold text-slate-900">Basic information</h2>
                        </div>
                        <div class="grid gap-5 p-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700">Name (English)</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $business->getRawOriginal('name')) }}" required
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="name_el" class="block text-sm font-medium text-slate-700">Όνομα (Ελληνικά)</label>
                                <input id="name_el" type="text" name="name_el" value="{{ old('name_el', $business->getRawOriginal('name_el')) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Προαιρετικό">
                            </div>
                            <div>
                                <label for="business_category_id" class="block text-sm font-medium text-slate-700">Category</label>
                                <select id="business_category_id" name="business_category_id"
                                        class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">—</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('business_category_id', $business->business_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if(isset($villages) && $villages->isNotEmpty())
                            <div>
                                <label for="village_id" class="block text-sm font-medium text-slate-700">Village</label>
                                <select id="village_id" name="village_id"
                                        class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">—</option>
                                    @foreach($villages as $v)
                                        <option value="{{ $v->id }}" {{ old('village_id', $business->villages->first()?->id) == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div>
                                <label for="description" class="block text-sm font-medium text-slate-700">Description (English)</label>
                                <textarea id="description" name="description" rows="4"
                                          class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('description', $business->getRawOriginal('description')) }}</textarea>
                            </div>
                            <div>
                                <label for="description_el" class="block text-sm font-medium text-slate-700">Περιγραφή (Ελληνικά)</label>
                                <textarea id="description_el" name="description_el" rows="4"
                                          class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Προαιρετικό">{{ old('description_el', $business->getRawOriginal('description_el')) }}</textarea>
                            </div>
                            <div>
                                <label for="video_url" class="block text-sm font-medium text-slate-700">Video URL</label>
                                <p class="mt-0.5 text-xs text-slate-500">YouTube or Vimeo link. Optional.</p>
                                <input id="video_url" type="url" name="video_url" value="{{ old('video_url', $business->video_url) }}" placeholder="https://www.youtube.com/watch?v=..."
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('video_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    {{-- LOCATION & CONTACT --}}
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                            <h2 class="text-lg font-semibold text-slate-900">Location & contact</h2>
                        </div>
                        <div class="grid gap-5 p-6">
                            <div>
                                <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                                <input id="address" type="text" name="address" value="{{ old('address', $business->address) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">{{ __('messages.businesses.map_location_label') }}</label>
                                <p class="mt-0.5 text-xs text-slate-500">{{ __('messages.businesses.map_location_help') }}</p>
                                <div class="mt-1.5 grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="map_lat" class="sr-only">Latitude</label>
                                        <input id="map_lat" type="text" name="map_lat" value="{{ old('map_lat', is_array($business->map_location) ? ($business->map_location['lat'] ?? '') : '') }}" placeholder="35.31" inputmode="decimal"
                                               class="block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <span class="mt-1 block text-xs text-slate-500">Latitude</span>
                                    </div>
                                    <div>
                                        <label for="map_lng" class="sr-only">Longitude</label>
                                        <input id="map_lng" type="text" name="map_lng" value="{{ old('map_lng', is_array($business->map_location) ? ($business->map_location['lng'] ?? '') : '') }}" placeholder="25.08" inputmode="decimal"
                                               class="block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <span class="mt-1 block text-xs text-slate-500">Longitude</span>
                                    </div>
                                </div>
                                @error('map_lat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                @error('map_lng')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone', $business->phone) }}"
                                           class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email', $business->email) }}"
                                           class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                            <div>
                                <label for="website" class="block text-sm font-medium text-slate-700">Website</label>
                                <input id="website" type="url" name="website" value="{{ old('website', $business->website) }}" placeholder="https://"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Social links</label>
                                <div class="grid gap-4 sm:grid-cols-3">
                                    <div>
                                        <label for="facebook" class="block text-xs text-slate-500">Facebook</label>
                                        <input id="facebook" type="url" name="facebook" value="{{ old('facebook', $business->social_links['facebook'] ?? '') }}" placeholder="https://facebook.com/..."
                                               class="mt-1 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <label for="instagram" class="block text-xs text-slate-500">Instagram</label>
                                        <input id="instagram" type="url" name="instagram" value="{{ old('instagram', $business->social_links['instagram'] ?? '') }}" placeholder="https://instagram.com/..."
                                               class="mt-1 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <label for="tripadvisor" class="block text-xs text-slate-500">Tripadvisor</label>
                                        <input id="tripadvisor" type="url" name="tripadvisor" value="{{ old('tripadvisor', $business->social_links['tripadvisor'] ?? '') }}" placeholder="https://tripadvisor.com/..."
                                               class="mt-1 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                </div>
                                @error('facebook')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                @error('instagram')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                @error('tripadvisor')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>
                </div>

                {{-- SIDEBAR --}}
                <aside class="space-y-6">
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                            <h2 class="text-base font-semibold text-slate-900">Publishing</h2>
                        </div>
                        <div class="space-y-5 p-5">
                            <div>
                                <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                                <select id="status" name="status"
                                        class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="pending" {{ old('status', $business->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $business->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="published" {{ old('status', $business->status) === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                            <div>
                                <label for="owner_id" class="block text-sm font-medium text-slate-700">Owner (user)</label>
                                <select id="owner_id" name="owner_id"
                                        class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">—</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ old('owner_id', $business->owner_id) == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <input type="checkbox" name="featured" value="1" {{ old('featured', $business->featured) ? 'checked' : '' }}
                                       class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>
                                    <span class="block text-sm font-medium text-slate-800">Featured</span>
                                    <span class="block text-xs text-slate-500">Show badge on listing pages.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <input type="checkbox" name="reviews_enabled" value="1" {{ old('reviews_enabled', $business->reviews_enabled ?? true) ? 'checked' : '' }}
                                       class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>
                                    <span class="block text-sm font-medium text-slate-800">Allow visitor reviews</span>
                                    <span class="block text-xs text-slate-500">Visitors can submit ratings and comments.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <input type="checkbox" name="reviews_require_approval" value="1" {{ old('reviews_require_approval', $business->reviews_require_approval ?? false) ? 'checked' : '' }}
                                       class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>
                                    <span class="block text-sm font-medium text-slate-800">Reviews require approval</span>
                                    <span class="block text-xs text-slate-500">New reviews stay hidden until approved.</span>
                                </span>
                            </label>
                        </div>
                    </section>

                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                            <h2 class="text-base font-semibold text-slate-900">Actions</h2>
                        </div>
                        <div class="space-y-3 p-5">
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                Update business
                            </button>
                            <a href="{{ route('admin.businesses.index') }}"
                               class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                Cancel
                            </a>
                        </div>
                    </section>
                </aside>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sortableEl = document.getElementById('gallery-sortable');
            if (sortableEl && typeof Sortable !== 'undefined') {
                new Sortable(sortableEl, { animation: 180, handle: '.gallery-drag-handle' });
            }

            document.querySelectorAll('.gallery-remove').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const keepInput = document.getElementById(this.dataset.keepId);
                    if (keepInput) keepInput.disabled = this.checked;
                });
            });

            let mediaPickerTarget = 'featured';
            const featuredInput = document.getElementById('featured_image');
            const featuredPreview = document.getElementById('featured-preview');
            const featuredPathInput = document.getElementById('featured_image_path');
            const browseFeaturedBtn = document.getElementById('browse-featured-media');
            const logoInput = document.getElementById('logo');
            const logoPreview = document.getElementById('logo-preview');
            const logoPathInput = document.getElementById('logo_path');
            const browseLogoBtn = document.getElementById('browse-logo-media');
            if (browseFeaturedBtn) {
                browseFeaturedBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    mediaPickerTarget = 'featured';
                    const w = window.open('{{ route("admin.media.index") }}?picker=1', 'media-picker', 'width=900,height=700,scrollbars=yes');
                    if (w) w.focus();
                });
            }
            if (browseLogoBtn) {
                browseLogoBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    mediaPickerTarget = 'logo';
                    const w = window.open('{{ route("admin.media.index") }}?picker=1', 'media-picker', 'width=900,height=700,scrollbars=yes');
                    if (w) w.focus();
                });
            }
            window.addEventListener('message', function (event) {
                if (event.origin !== window.location.origin || event.data?.type !== 'media-picked') return;
                const path = event.data.path;
                const url = event.data.url;
                if (mediaPickerTarget === 'logo') {
                    if (logoPathInput) logoPathInput.value = path || '';
                    if (logoPreview && url) logoPreview.src = url;
                    if (logoInput) logoInput.value = '';
                } else {
                    if (featuredPathInput) featuredPathInput.value = path || '';
                    if (featuredPreview && url) featuredPreview.src = url;
                    if (featuredInput) featuredInput.value = '';
                }
            });
            if (featuredInput && featuredPreview) {
                featuredInput.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    if (featuredPathInput) featuredPathInput.value = '';
                    const reader = new FileReader();
                    reader.onload = function (ev) { featuredPreview.src = ev.target.result; };
                    reader.readAsDataURL(file);
                });
            }
            if (logoInput && logoPreview) {
                logoInput.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    if (logoPathInput) logoPathInput.value = '';
                    const reader = new FileReader();
                    reader.onload = function (ev) { logoPreview.src = ev.target.result; };
                    reader.readAsDataURL(file);
                });
            }

            const galleryInput = document.getElementById('gallery');
            const galleryPreview = document.getElementById('gallery-new-preview');
            if (galleryInput && galleryPreview) {
                galleryInput.addEventListener('change', function (e) {
                    galleryPreview.innerHTML = '';
                    Array.from(e.target.files || []).forEach(function (file) {
                        if (!file.type.startsWith('image/')) return;
                        const reader = new FileReader();
                        reader.onload = function (ev) {
                            const card = document.createElement('div');
                            card.className = 'overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm';
                            card.innerHTML = '<img src="' + ev.target.result + '" class="h-40 w-full object-cover" alt=""><div class="p-3"><p class="truncate text-sm font-medium text-slate-700">' + file.name + '</p><p class="mt-1 text-xs text-slate-500">' + Math.round(file.size / 1024) + ' KB</p></div>';
                            galleryPreview.appendChild(card);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });
    </script>
@endsection
