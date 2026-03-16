@extends('layouts.admin')

@section('title', 'Edit Place')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">Admin / Places</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Edit place</h1>
                <p class="mt-1 text-sm text-slate-500">Update media, content, contact details, and publishing options.</p>
            </div>

            <a href="{{ route('admin.places.index') }}"
               class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                Back to places
            </a>
        </div>

        <form method="POST"
              action="{{ route('admin.places.update', $place) }}"
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
                            <p class="mt-1 text-sm text-slate-500">Upload featured image and manage the gallery visually.</p>
                        </div>

                        <div class="space-y-8 p-6">
                            {{-- Featured image --}}
                            <div>
                                <label for="featured_image" class="block text-sm font-semibold text-slate-800">
                                    Featured image
                                </label>
                                <p class="mt-1 text-xs text-slate-500">
                                    JPG, JPEG, PNG, WEBP up to 2MB. Leave empty to keep the current image.
                                </p>

                                <div class="mt-4 grid gap-4 lg:grid-cols-[220px_minmax(0,1fr)]">
                                    <div id="featured-preview-wrap" class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                        <x-image-or-placeholder id="featured-preview" :src="$place->featured_image" :alt="$place->title" class="h-52 w-full object-cover" />
                                    </div>

                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-5 space-y-3">
                                        <input type="hidden" name="featured_image_path" id="featured_image_path" value="">
                                        <input id="featured_image"
                                               type="file"
                                               name="featured_image"
                                               accept="image/jpeg,image/jpg,image/png,image/webp"
                                               class="block w-full text-sm text-slate-500
                                                      file:mr-4 file:rounded-xl file:border-0
                                                      file:bg-emerald-600 file:px-4 file:py-2.5
                                                      file:text-sm file:font-semibold file:text-white
                                                      hover:file:bg-emerald-700">

                                        <p class="text-xs text-slate-500">or</p>
                                        <a href="#" id="browse-featured-media" target="_blank" rel="noopener"
                                           class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Browse Media
                                        </a>

                                        <p class="mt-3 text-xs leading-5 text-slate-500">
                                            Recommended: landscape, clean, bright, high quality.
                                        </p>

                                        @error('featured_image')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Existing gallery --}}
                            <div>
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-800">Current gallery</label>
                                        <p class="mt-1 text-xs text-slate-500">
                                            Drag to reorder. Check "Remove image" to delete.
                                        </p>
                                    </div>
                                </div>

                                <ul id="gallery-sortable" class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                    @foreach($place->gallery ?? [] as $idx => $path)
                                        <li class="gallery-sort-item group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                            <div class="relative">
                                                <x-image-or-placeholder :src="$path" alt="Gallery image" class="h-40 w-full object-cover" />

                                                <div class="absolute left-3 top-3">
                                                    <button type="button"
                                                            class="gallery-drag-handle inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-600 shadow cursor-grab active:cursor-grabbing"
                                                            title="Drag to reorder">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="space-y-3 p-4">
                                                <p class="truncate text-sm font-medium text-slate-700">{{ basename($path) }}</p>

                                                <label class="inline-flex items-center text-sm text-slate-600">
                                                    <input type="checkbox"
                                                           name="gallery_remove[]"
                                                           value="{{ $path }}"
                                                           class="gallery-remove rounded border-slate-300 text-red-600 focus:ring-red-500"
                                                           data-keep-id="gallery-keep-{{ $idx }}">
                                                    <span class="ml-2">Remove image</span>
                                                </label>

                                                <input type="hidden"
                                                       name="gallery_keep[]"
                                                       value="{{ $path }}"
                                                       id="gallery-keep-{{ $idx }}"
                                                       class="gallery-keep-input">
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                @error('gallery_keep')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- New gallery uploads --}}
                            <div>
                                <label for="gallery" class="block text-sm font-semibold text-slate-800">
                                    Add gallery images
                                </label>
                                <p class="mt-1 text-xs text-slate-500">
                                    You can select multiple images at once.
                                </p>

                                <div class="mt-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-5">
                                    <input id="gallery"
                                           type="file"
                                           name="gallery[]"
                                           multiple
                                           accept="image/jpeg,image/jpg,image/png,image/webp"
                                           class="block w-full text-sm text-slate-500
                                                  file:mr-4 file:rounded-xl file:border-0
                                                  file:bg-slate-900 file:px-4 file:py-2.5
                                                  file:text-sm file:font-semibold file:text-white
                                                  hover:file:bg-slate-800">

                                    <div id="gallery-new-preview" class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3"></div>
                                </div>

                                @error('gallery')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @error('gallery.*')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                <label for="title" class="block text-sm font-medium text-slate-700">Title (English)</label>
                                <input id="title" type="text" name="title" value="{{ old('title', $place->getRawOriginal('title')) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="title_el" class="block text-sm font-medium text-slate-700">Τίτλος (Ελληνικά)</label>
                                <input id="title_el" type="text" name="title_el" value="{{ old('title_el', $place->getRawOriginal('title_el')) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Προαιρετικό">
                            </div>

                            <div>
                                <label for="place_category_id" class="block text-sm font-medium text-slate-700">Category</label>
                                <select id="place_category_id" name="place_category_id"
                                        class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">—</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('place_category_id', $place->place_category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
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
                                        <option value="{{ $v->id }}" {{ old('village_id', $place->villages->first()?->id) == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div>
                                <label for="short_description" class="block text-sm font-medium text-slate-700">Short description (English)</label>
                                <textarea id="short_description" name="short_description" rows="3"
                                          class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('short_description', $place->getRawOriginal('short_description')) }}</textarea>
                            </div>
                            <div>
                                <label for="short_description_el" class="block text-sm font-medium text-slate-700">Σύντομη περιγραφή (Ελληνικά)</label>
                                <textarea id="short_description_el" name="short_description_el" rows="3"
                                          class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Προαιρετικό">{{ old('short_description_el', $place->getRawOriginal('short_description_el')) }}</textarea>
                            </div>

                            <div>
                                <label for="full_content" class="block text-sm font-medium text-slate-700">Full description (English)</label>
                                <textarea id="full_content" name="full_content" rows="8"
                                          class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('full_content', $place->getRawOriginal('full_content')) }}</textarea>
                            </div>
                            <div>
                                <label for="full_content_el" class="block text-sm font-medium text-slate-700">Πλήρης περιγραφή (Ελληνικά)</label>
                                <textarea id="full_content_el" name="full_content_el" rows="8"
                                          class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Προαιρετικό">{{ old('full_content_el', $place->getRawOriginal('full_content_el')) }}</textarea>
                            </div>
                        </div>
                    </section>

                    {{-- CONTACT --}}
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                            <h2 class="text-lg font-semibold text-slate-900">Location & contact</h2>
                        </div>

                        <div class="grid gap-5 p-6">
                            <div>
                                <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                                <input id="address" type="text" name="address" value="{{ old('address', $place->address) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            @php $coords = $place->coordinates ?? []; @endphp
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="map_lat" class="block text-sm font-medium text-slate-700">Latitude</label>
                                    <input id="map_lat" type="text" name="map_lat" value="{{ old('map_lat', $coords['lat'] ?? '') }}"
                                           class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="map_lng" class="block text-sm font-medium text-slate-700">Longitude</label>
                                    <input id="map_lng" type="text" name="map_lng" value="{{ old('map_lng', $coords['lng'] ?? '') }}"
                                           class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone', $place->phone) }}"
                                           class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email', $place->email) }}"
                                           class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-slate-700">Website</label>
                                <input id="website" type="url" name="website" value="{{ old('website', $place->website) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label for="video_url" class="block text-sm font-medium text-slate-700">Video URL</label>
                                <input id="video_url" type="url" name="video_url" value="{{ old('video_url', $place->video_url) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
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
                                <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort order</label>
                                <input id="sort_order" type="number" name="sort_order" min="0"
                                       value="{{ old('sort_order', $place->sort_order) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                                <select id="status" name="status"
                                        class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="draft" {{ old('status', $place->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $place->status) === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <input type="checkbox" name="featured" value="1"
                                       {{ old('featured', $place->featured) ? 'checked' : '' }}
                                       class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>
                                    <span class="block text-sm font-medium text-slate-800">Featured place</span>
                                    <span class="block text-xs text-slate-500">Show badge on listing pages.</span>
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
                                Update place
                            </button>

                            <a href="{{ route('admin.places.index') }}"
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
                new Sortable(sortableEl, {
                    animation: 180,
                    handle: '.gallery-drag-handle'
                });
            }

            document.querySelectorAll('.gallery-remove').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const keepInput = document.getElementById(this.dataset.keepId);
                    if (keepInput) keepInput.disabled = this.checked;
                });
            });

            function getPreviewImg(wrapId, fallbackId) {
                const wrap = document.getElementById(wrapId);
                if (wrap) {
                    const img = wrap.querySelector('img');
                    if (img) return img;
                }
                return document.getElementById(fallbackId);
            }

            const featuredInput = document.getElementById('featured_image');
            const featuredPreview = getPreviewImg('featured-preview-wrap', 'featured-preview');
            const featuredPathInput = document.getElementById('featured_image_path');
            const browseFeaturedBtn = document.getElementById('browse-featured-media');

            if (browseFeaturedBtn) {
                browseFeaturedBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.open('{{ route("admin.media.index") }}?picker=1', 'media-picker', 'width=900,height=700,scrollbars=yes');
                });
            }

            window.addEventListener('message', function (event) {
                if (event.origin !== window.location.origin || event.data?.type !== 'media-picked') return;
                const path = event.data.path;
                const url = event.data.url;
                if (featuredPathInput) featuredPathInput.value = path || '';
                if (featuredPreview && url) featuredPreview.src = url;
                if (featuredInput) featuredInput.value = '';
            });

            if (featuredInput && featuredPreview) {
                featuredInput.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    if (featuredPathInput) featuredPathInput.value = '';
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        featuredPreview.src = ev.target.result;
                    };
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

                            card.innerHTML = `
                                <img src="${ev.target.result}" class="h-40 w-full object-cover" alt="">
                                <div class="p-3">
                                    <p class="truncate text-sm font-medium text-slate-700">${file.name}</p>
                                    <p class="mt-1 text-xs text-slate-500">${Math.round(file.size / 1024)} KB</p>
                                </div>
                            `;

                            galleryPreview.appendChild(card);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });
    </script>
@endsection