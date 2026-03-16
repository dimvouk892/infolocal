@extends('layouts.dashboard')

@section('title', __('messages.dashboard.edit_business'))

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">{{ __('messages.dashboard.label') }}</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">{{ __('messages.dashboard.edit_business') }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $business->name }}</p>
            </div>

            <a href="{{ route('dashboard.index') }}"
               class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                {{ __('messages.dashboard.back') }}
            </a>
        </div>

        <form method="POST" action="{{ route('dashboard.business.update', $business) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
                <div class="space-y-6">

                    {{-- MEDIA --}}
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.dashboard.featured_image') }}</h2>
                            <p class="mt-1 text-sm text-slate-500">Logo, featured image and gallery. JPG/PNG/WEBP up to 2MB. Leave empty to keep current.</p>
                        </div>

                        <div class="space-y-8 p-6">
                            <div>
                                <label for="logo" class="block text-sm font-semibold text-slate-800">Logo</label>
                                <p class="mt-1 text-xs text-slate-500">Official business logo (shown on cards).</p>
                                <div class="mt-4 grid gap-4 lg:grid-cols-[180px_minmax(0,1fr)]">
                                    <div class="flex aspect-square max-h-[180px] w-full items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                        <x-image-or-placeholder id="logo-preview" :src="$business->logo" :alt="$business->name" class="max-h-full w-auto max-w-full object-contain" />
                                    </div>
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-5">
                                        <input id="logo" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/jpg,image/png,image/webp"
                                               class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-700">
                                        @error('logo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="featured_image" class="block text-sm font-semibold text-slate-800">Featured image</label>
                                <div class="mt-4 grid gap-4 lg:grid-cols-[220px_minmax(0,1fr)]">
                                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                        <x-image-or-placeholder id="featured-preview" :src="$business->featured_image" :alt="$business->name" class="h-52 w-full object-cover" />
                                    </div>
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-5">
                                        <input id="featured_image" type="file" name="featured_image" accept=".jpg,.jpeg,image/jpeg,image/jpg"
                                               class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-700">
                                        @error('featured_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Gallery --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-800">Gallery</label>
                                <p class="mt-1 text-xs text-slate-500">Drag to reorder. Check "Remove image" to delete.</p>

                                <ul id="gallery-sortable-dash" class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                    @foreach(array_values(array_filter($business->gallery ?? [], fn($p) => is_string($p) && trim($p ?? '') !== '')) as $idx => $path)
                                        <li class="gallery-sort-item group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                            <div class="relative">
                                                <x-image-or-placeholder :src="$path" alt="Gallery" class="h-40 w-full object-cover" />
                                                <div class="absolute left-3 top-3">
                                                    <span class="gallery-drag-handle inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-600 shadow cursor-grab active:cursor-grabbing">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="space-y-3 p-4">
                                                <p class="truncate text-sm font-medium text-slate-700">{{ basename($path) }}</p>
                                                <label class="inline-flex items-center text-sm text-slate-600">
                                                    <input type="checkbox" name="gallery_remove[]" value="{{ $path }}" class="gallery-remove rounded border-slate-300 text-red-600 focus:ring-red-500" data-keep-id="dkeep-{{ $idx }}">
                                                    <span class="ml-2">Remove</span>
                                                </label>
                                                <input type="hidden" name="gallery_keep[]" value="{{ $path }}" id="dkeep-{{ $idx }}">
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="mt-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-5">
                                    <input type="file" name="gallery[]" accept=".jpg,.jpeg,image/jpeg,image/jpg" multiple
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
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.dashboard.name') }}</h2>
                        </div>
                        <div class="grid gap-5 p-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700">{{ __('messages.dashboard.name') }}</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $business->name) }}" required
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-slate-700">{{ __('messages.dashboard.description') }}</label>
                                <textarea id="description" name="description" rows="4"
                                          class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('description', $business->description) }}</textarea>
                                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="video_url" class="block text-sm font-medium text-slate-700">{{ __('messages.businesses.video_url_label') }}</label>
                                <p class="mt-0.5 text-xs text-slate-500">{{ __('messages.businesses.video_url_help') }}</p>
                                <input id="video_url" type="url" name="video_url" value="{{ old('video_url', $business->video_url) }}" placeholder="https://www.youtube.com/watch?v=..."
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('video_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            @if($categories->isNotEmpty())
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">{{ __('messages.dashboard.category') }}</label>
                                    <p class="mt-1.5 text-sm text-slate-600">{{ $business->category?->name ?? '—' }}</p>
                                </div>
                            @endif
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
                        </div>
                    </section>

                    {{-- CONTACT --}}
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.contact.title') }}</h2>
                        </div>
                        <div class="grid gap-5 p-6">
                            <div>
                                <label for="address" class="block text-sm font-medium text-slate-700">{{ __('messages.dashboard.address') }}</label>
                                <input id="address" type="text" name="address" value="{{ old('address', $business->address) }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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
                                    <label for="phone" class="block text-sm font-medium text-slate-700">{{ __('messages.dashboard.phone') }}</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone', $business->phone) }}"
                                           class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700">{{ __('messages.dashboard.email') }}</label>
                                    <input id="email" type="email" name="email" value="{{ old('email', $business->email) }}"
                                           class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label for="website" class="block text-sm font-medium text-slate-700">{{ __('messages.dashboard.website') }}</label>
                                <input id="website" type="url" name="website" value="{{ old('website', $business->website) }}" placeholder="https://"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('website')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="opening_hours" class="block text-sm font-medium text-slate-700">{{ __('messages.dashboard.opening_hours') }}</label>
                                <input id="opening_hours" type="text" name="opening_hours"
                                       value="{{ old('opening_hours', is_array($business->opening_hours) && isset($business->opening_hours['description']) ? $business->opening_hours['description'] : (is_string($business->opening_hours) ? $business->opening_hours : '')) }}"
                                       placeholder="{{ __('messages.dashboard.opening_hours_help') }}"
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('opening_hours')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    {{-- SOCIAL --}}
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.dashboard.social_links') }}</h2>
                        </div>
                        <div class="grid gap-5 p-6 sm:grid-cols-3">
                            <div>
                                <label for="facebook" class="block text-sm font-medium text-slate-700">Facebook</label>
                                <input id="facebook" type="url" name="facebook" value="{{ old('facebook', $business->social_links['facebook'] ?? '') }}" placeholder="https://facebook.com/..."
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('facebook')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="instagram" class="block text-sm font-medium text-slate-700">Instagram</label>
                                <input id="instagram" type="url" name="instagram" value="{{ old('instagram', $business->social_links['instagram'] ?? '') }}" placeholder="https://instagram.com/..."
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('instagram')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="tripadvisor" class="block text-sm font-medium text-slate-700">Tripadvisor</label>
                                <input id="tripadvisor" type="url" name="tripadvisor" value="{{ old('tripadvisor', $business->social_links['tripadvisor'] ?? '') }}" placeholder="https://tripadvisor.com/..."
                                       class="mt-1.5 block w-full rounded-2xl border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('tripadvisor')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>
                </div>

                {{-- SIDEBAR --}}
                <aside class="space-y-6">
                    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                            <h2 class="text-base font-semibold text-slate-900">Actions</h2>
                        </div>
                        <div class="space-y-4 p-5">
                            <label class="block rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                                <span class="flex items-start gap-3">
                                    <input type="checkbox" name="reviews_enabled" value="1" {{ old('reviews_enabled', $business->reviews_enabled ?? true) ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-800">Allow visitor reviews</span>
                                        <span class="mt-1 block text-xs leading-5 text-slate-500">The business owner can decide if visitors are allowed to leave reviews on this page.</span>
                                    </span>
                                </span>
                            </label>
                            <label class="block rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                                <span class="flex items-start gap-3">
                                    <input type="checkbox" name="reviews_require_approval" value="1" {{ old('reviews_require_approval', $business->reviews_require_approval ?? false) ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-800">Reviews require admin approval</span>
                                        <span class="mt-1 block text-xs leading-5 text-slate-500">If checked, new reviews will not appear until an admin approves them in the admin panel.</span>
                                    </span>
                                </span>
                            </label>
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                {{ __('messages.dashboard.save') }}
                            </button>
                            <a href="{{ route('dashboard.index') }}"
                               class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                {{ __('messages.dashboard.back') }}
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
            var el = document.getElementById('gallery-sortable-dash');
            if (el && typeof Sortable !== 'undefined' && el.children.length > 0) {
                new Sortable(el, { animation: 180, handle: '.gallery-drag-handle' });
            }

            document.querySelectorAll('.gallery-remove').forEach(function (cb) {
                cb.addEventListener('change', function () {
                    var k = document.getElementById(this.getAttribute('data-keep-id'));
                    if (k) k.disabled = this.checked;
                });
            });

            var featuredInput = document.getElementById('featured_image');
            var featuredPreview = document.getElementById('featured-preview');
            if (featuredInput && featuredPreview) {
                featuredInput.addEventListener('change', function (e) {
                    var file = e.target.files && e.target.files[0];
                    if (!file) return;
                    var reader = new FileReader();
                    reader.onload = function (ev) { featuredPreview.src = ev.target.result; };
                    reader.readAsDataURL(file);
                });
            }
            var logoInput = document.getElementById('logo');
            var logoPreview = document.getElementById('logo-preview');
            if (logoInput && logoPreview) {
                logoInput.addEventListener('change', function (e) {
                    var file = e.target.files && e.target.files[0];
                    if (!file) return;
                    var reader = new FileReader();
                    reader.onload = function (ev) { logoPreview.src = ev.target.result; };
                    reader.readAsDataURL(file);
                });
            }

            var galleryInput = document.querySelector('input[name="gallery[]"]');
            var galleryPreview = document.getElementById('gallery-new-preview');
            if (galleryInput && galleryPreview) {
                galleryInput.addEventListener('change', function (e) {
                    galleryPreview.innerHTML = '';
                    Array.from(e.target.files || []).forEach(function (file) {
                        if (!file.type.startsWith('image/')) return;
                        var reader = new FileReader();
                        reader.onload = function (ev) {
                            var card = document.createElement('div');
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
