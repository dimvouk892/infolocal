@extends('layouts.admin')

@section('title', 'Settings')
@section('page_title', 'Settings')

@section('content')
    @php
        // Simplified tabs: remove Colors, Navigation & Footer, Content, and Page content
        $settingsTabs = [
            ['id' => 'general', 'label' => 'General', 'description' => 'Site identity and availability.'],
            ['id' => 'seo', 'label' => 'SEO', 'description' => 'Meta tags, Open Graph and search engines.'],
            ['id' => 'logo', 'label' => 'Logo', 'description' => 'PNG logos, favicon, sizing, and alignment.'],
            ['id' => 'homepage', 'label' => 'Homepage', 'description' => 'Main image and hero presentation.'],
            ['id' => 'legal', 'label' => 'Legal', 'description' => 'Privacy policy and Terms of use pages.'],
            ['id' => 'contact', 'label' => 'Contact', 'description' => 'Contact email, phone and social links.'],
        ];
    @endphp
    <div
        x-data="{
            tab: @js(old('current_tab', request('tab', 'general'))),
            colorGroup: @js(old('current_color_group', request('color_group', 'admin')))
        }"
        class="max-w-7xl"
    >
        <form method="POST" action="{{ route('admin.settings.update') }}" class="grid gap-6 xl:grid-cols-[280px,minmax(0,1fr)]">
            @csrf
            @method('PUT')
            <input type="hidden" name="current_tab" :value="tab">
            <input type="hidden" name="current_color_group" :value="colorGroup">

            <x-ui.card class="h-fit xl:sticky xl:top-24">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Settings</p>
                    <h2 class="text-lg font-semibold text-primary">Admin Control Center</h2>
                    <p class="text-sm text-slate-500">Organized settings for design, logos, homepage content, and contact details.</p>
                </div>

                <div class="mt-6 space-y-2">
                    @foreach($settingsTabs as $settingsTab)
                        <button
                            type="button"
                            @click="tab = '{{ $settingsTab['id'] }}'"
                            :class="tab === '{{ $settingsTab['id'] }}' ? 'border-accent bg-secondary text-primary shadow-sm' : 'border-slate-200 bg-white text-slate-600 hover:border-primary/20 hover:text-primary'"
                            class="flex w-full items-start gap-3 rounded-2xl border px-4 py-3 text-left transition"
                        >
                            <span class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/80 text-xs font-semibold text-primary">{{ $loop->iteration }}</span>
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold">{{ $settingsTab['label'] }}</span>
                                <span class="mt-1 block text-xs text-slate-500">{{ $settingsTab['description'] }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </x-ui.card>

            <div class="space-y-6">
            <x-ui.card class="border-slate-200/80">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">CMS Settings</p>
                        <h1 class="mt-1 text-2xl font-semibold text-primary">Website Management</h1>
                        <p class="mt-2 text-sm text-slate-500">The admin can manage branding, homepage visuals, content blocks, and footer details from here.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">
                        Active section:
                        <span class="font-semibold text-primary" x-text="tab.charAt(0).toUpperCase() + tab.slice(1)"></span>
                    </div>
                </div>
            </x-ui.card>

            {{-- General --}}
            <div x-show="tab === 'general'" x-cloak class="space-y-4">
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Site availability</h3>
                        <p class="text-sm text-slate-500">Show an "Under Construction" page to visitors. Admins can always access the site and the admin panel.</p>
                    </div>
                    <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50/50 p-4">
                        <input type="hidden" name="site_under_construction" value="0">
                        <input type="checkbox" name="site_under_construction" value="1" id="site_under_construction"
                               {{ filter_var(old('site_under_construction', optional($settings)->site_under_construction ?? 0), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}
                               class="mt-1 h-5 w-5 rounded border-slate-300 text-accent focus:ring-accent">
                        <label for="site_under_construction" class="flex-1">
                            <span class="block text-sm font-semibold text-slate-800">Site Under Construction (MainMachine)</span>
                            <span class="mt-0.5 block text-xs text-slate-500">When enabled, visitors see only the "Under Construction" page. You can still use the site and admin as usual.</span>
                        </label>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">General</h3>
                        <p class="text-sm text-slate-500">Site title and tagline. SEO fields are in the SEO tab.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="Site title" name="site_title" :value="old('site_title', optional($settings)->site_title ?? '')" placeholder="Visit Mylopotamos" />
                        <x-ui.input label="Tagline (header)" name="tagline" :value="old('tagline', optional($settings)->tagline ?? '')" placeholder="Discover Melidoni, Margarites, Perama & the coast" />
                    </div>
                </x-ui.card>
            </div>

            {{-- SEO --}}
            <div x-show="tab === 'seo'" x-cloak class="space-y-4">
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">SEO – Search & social sharing</h3>
                        <p class="text-sm text-slate-500">Default meta tags for all pages. Used by search engines and when the site is shared on Facebook, Twitter, etc.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="Meta title (default)" name="meta_title" :value="old('meta_title', optional($settings)->meta_title ?? '')" placeholder="Visit Mylopotamos – Local travel guide" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Meta description (default)</label>
                            <textarea name="meta_description" rows="3" class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-accent focus:ring-accent" placeholder="Short description for search engines (about 150–160 characters).">{{ old('meta_description', optional($settings)->meta_description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Meta keywords (optional)</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', optional($settings)->meta_keywords ?? '') }}" class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-accent focus:ring-accent" placeholder="tourism, Mylopotamos, Crete, attractions">
                            <p class="mt-1 text-xs text-slate-500">Comma-separated keywords. Search engines use these less but they can still help.</p>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Open Graph & social tags</h3>
                        <p class="text-sm text-slate-500">Image and site name shown when links are shared (Facebook, Twitter, LinkedIn, etc.).</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="OG image (URL or path)" name="og_image" :value="old('og_image', optional($settings)->og_image ?? '')" placeholder="e.g. /storage/og-image.jpg or full URL" />
                        <p class="text-xs text-slate-500">Use a full URL (https://…) or a path under storage (e.g. og/default.jpg). Recommended size about 1200×630 px.</p>
                        <x-ui.input label="OG site name" name="og_site_name" :value="old('og_site_name', optional($settings)->og_site_name ?? '')" placeholder="Leave empty to use Site title from General" />
                        <p class="text-xs text-slate-500">Name shown next to the link when shared. If empty, the Site title from the General tab is used.</p>
                    </div>
                </x-ui.card>
            </div>

            {{-- Colors (disabled) --}}
            <div x-show="false" x-cloak class="space-y-4">
                @php
                    $colorGroups = [
                        [
                            'id' => 'admin',
                            'title' => 'Admin Panel Colors',
                            'description' => 'Colors used only for the admin dashboard and CMS surfaces.',
                            'fields' => [
                                ['key' => 'admin_primary_color', 'label' => 'Admin Primary', 'default' => '#1E3A5F'],
                                ['key' => 'admin_primary_hover_color', 'label' => 'Admin Primary Hover', 'default' => '#2A4A75'],
                                ['key' => 'admin_background_color', 'label' => 'Admin Background', 'default' => '#F8FAFC'],
                                ['key' => 'admin_sidebar_background_color', 'label' => 'Admin Sidebar Background', 'default' => '#1E3A5F'],
                                ['key' => 'admin_text_primary_color', 'label' => 'Admin Text Primary', 'default' => '#0F172A'],
                                ['key' => 'admin_text_secondary_color', 'label' => 'Admin Text Secondary', 'default' => '#64748B'],
                                ['key' => 'admin_sidebar_active_color', 'label' => 'Admin Sidebar Active Menu', 'default' => '#10B981'],
                            ],
                        ],
                        [
                            'id' => 'user',
                            'title' => 'User Interface Colors',
                            'description' => 'Primary interactive colors for the frontend experience.',
                            'fields' => [
                                ['key' => 'user_primary_color', 'label' => 'User Primary', 'default' => '#1E3A5F'],
                                ['key' => 'user_primary_hover_color', 'label' => 'User Primary Hover', 'default' => '#2A4A75'],
                                ['key' => 'user_secondary_color', 'label' => 'User Secondary', 'default' => '#E0F2FE'],
                                ['key' => 'user_secondary_hover_color', 'label' => 'User Secondary Hover', 'default' => '#BAE6FD'],
                                ['key' => 'accent_color', 'label' => 'Accent', 'default' => '#10B981'],
                                ['key' => 'accent_hover_color', 'label' => 'Accent Hover', 'default' => '#059669'],
                                ['key' => 'button_background_color', 'label' => 'Button Background', 'default' => '#10B981'],
                                ['key' => 'button_hover_color', 'label' => 'Button Hover', 'default' => '#059669'],
                                ['key' => 'link_color', 'label' => 'Link Color', 'default' => '#10B981'],
                                ['key' => 'link_hover_color', 'label' => 'Link Hover', 'default' => '#059669'],
                            ],
                        ],
                        [
                            'id' => 'layout',
                            'title' => 'Website Layout Colors',
                            'description' => 'Main structural colors for header, footer, sections, and borders.',
                            'fields' => [
                                ['key' => 'header_background_color', 'label' => 'Header Background', 'default' => '#1E3A5F'],
                                ['key' => 'header_text_color', 'label' => 'Header Text', 'default' => '#FFFFFF'],
                                ['key' => 'nav_hover_color', 'label' => 'Nav Menu Hover', 'default' => '#10B981'],
                                ['key' => 'footer_background_color', 'label' => 'Footer Background', 'default' => '#1E3A5F'],
                                ['key' => 'footer_text_color', 'label' => 'Footer Text', 'default' => '#FFFFFF'],
                                ['key' => 'body_background_color', 'label' => 'Body Background', 'default' => '#F8FAFC'],
                                ['key' => 'section_background_color', 'label' => 'Section Background', 'default' => '#FFFFFF'],
                                ['key' => 'border_color', 'label' => 'Border Color', 'default' => '#CBD5E1'],
                            ],
                        ],
                        [
                            'id' => 'semantic',
                            'title' => 'Semantic Colors',
                            'description' => 'Feedback colors for system states like success, warning, and errors.',
                            'fields' => [
                                ['key' => 'success_color', 'label' => 'Success', 'default' => '#22C55E'],
                                ['key' => 'warning_color', 'label' => 'Warning', 'default' => '#F59E0B'],
                                ['key' => 'error_color', 'label' => 'Error', 'default' => '#EF4444'],
                                ['key' => 'info_color', 'label' => 'Info', 'default' => '#3B82F6'],
                            ],
                        ],
                        [
                            'id' => 'neutral',
                            'title' => 'Neutral Colors',
                            'description' => 'Base backgrounds, text tones, and border levels used across the UI.',
                            'fields' => [
                                ['key' => 'main_background_color', 'label' => 'Main Background', 'default' => '#F8FAFC'],
                                ['key' => 'soft_background_color', 'label' => 'Soft Background', 'default' => '#F1F5F9'],
                                ['key' => 'text_primary_color', 'label' => 'Text Primary', 'default' => '#0F172A'],
                                ['key' => 'text_secondary_color', 'label' => 'Text Secondary', 'default' => '#334155'],
                                ['key' => 'text_muted_color', 'label' => 'Text Muted', 'default' => '#64748B'],
                                ['key' => 'border_light_color', 'label' => 'Border Light', 'default' => '#E2E8F0'],
                                ['key' => 'border_strong_color', 'label' => 'Border Strong', 'default' => '#94A3B8'],
                            ],
                        ],
                    ];
                @endphp

                <x-ui.card class="border-slate-200/80">
                    <div class="space-y-2">
                        <h3 class="text-base font-semibold text-primary">Brand Color Management</h3>
                        <p class="text-sm text-slate-500">Manage grouped brand colors with a clean structure, live preview, and reusable settings for both the website and admin panel.</p>
                    </div>
                </x-ui.card>

                <div class="space-y-4">
                    @foreach($colorGroups as $group)
                        <x-ui.card class="border-primary/10">
                            <button
                                type="button"
                                @click="colorGroup = colorGroup === '{{ $group['id'] }}' ? null : '{{ $group['id'] }}'"
                                class="flex w-full items-center justify-between gap-4 text-left"
                            >
                                <div>
                                    <h3 class="text-base font-semibold text-primary">{{ $group['title'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $group['description'] }}</p>
                                </div>

                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-secondary text-primary">
                                    <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': colorGroup === '{{ $group['id'] }}' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </button>

                            <div x-show="colorGroup === '{{ $group['id'] }}'" x-cloak class="mt-6 grid gap-4 lg:grid-cols-2">
                                @foreach($group['fields'] as $field)
                                    <x-ui.color-field
                                        :name="$field['key']"
                                        :label="$field['label']"
                                        :value="data_get($settings ?? null, $field['key'], $field['default'])"
                                        :default="$field['default']"
                                    />
                                @endforeach
                            </div>
                        </x-ui.card>
                    @endforeach
                </div>
            </div>

            {{-- Logo --}}
            <div x-show="tab === 'logo'" x-cloak class="space-y-4">
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-sm font-semibold text-primary">Logos & favicon</h3>
                        <p class="text-sm text-slate-500">Manage PNG logo image, size, max width, and alignment for frontend and admin panels.</p>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-2">
                        <div
                            x-data="{
                                frontendLogo: @js(old('logo_frontend', optional($settings)->logo_frontend ?? '')),
                                frontendHeight: {{ max(24, min(220, (int) old('logo_frontend_height', optional($settings)->logo_frontend_height ?? 80))) }},
                                frontendMaxWidth: {{ max(60, min(480, (int) old('logo_frontend_max_width', optional($settings)->logo_frontend_max_width ?? 260))) }},
                                frontendAlignment: @js(old('logo_frontend_alignment', optional($settings)->logo_frontend_alignment ?? 'left'))
                            }"
                            class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4"
                        >
                            <h4 class="text-sm font-semibold text-slate-900">Frontend Logo</h4>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-700">PNG logo image</label>
                                <input type="text" name="logo_frontend" x-model="frontendLogo" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="path/to/logo.png or full URL">
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Logo height</p>
                                        <p class="text-xs text-slate-500">The PNG keeps its proportions automatically.</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700" x-text="frontendHeight + 'px'"></span>
                                </div>
                                <input type="range" name="logo_frontend_height" x-model="frontendHeight" min="24" max="220" step="1" class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-accent">
                                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                    <span>24px</span>
                                    <span>220px</span>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Logo max width</p>
                                        <p class="text-xs text-slate-500">Sets the allowed width without stretching the image.</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700" x-text="frontendMaxWidth + 'px'"></span>
                                </div>
                                <input type="range" name="logo_frontend_max_width" x-model="frontendMaxWidth" min="60" max="480" step="1" class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-accent">
                                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                    <span>60px</span>
                                    <span>480px</span>
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-700">Height value (px)</label>
                                    <input type="number" x-model="frontendHeight" min="24" max="220" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-700">Max width value (px)</label>
                                    <input type="number" x-model="frontendMaxWidth" min="60" max="480" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                </div>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-700">Alignment</label>
                                <select name="logo_frontend_alignment" x-model="frontendAlignment" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                    <option value="left" {{ old('logo_frontend_alignment', optional($settings)->logo_frontend_alignment ?? 'left') === 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="center" {{ old('logo_frontend_alignment', optional($settings)->logo_frontend_alignment ?? 'left') === 'center' ? 'selected' : '' }}>Center</option>
                                </select>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Preview</p>
                                <div class="flex min-h-[120px] rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 items-center" :class="frontendAlignment === 'center' ? 'justify-center' : 'justify-start'">
                                    @php $previewLogo = old('logo_frontend', optional($settings)->logo_frontend ?? ''); @endphp
                                    <template x-if="frontendLogo">
                                        <img
                                            src="{{ (str_starts_with($previewLogo, 'http://') || str_starts_with($previewLogo, 'https://')) ? $previewLogo : ($previewLogo ? asset('storage/' . ltrim($previewLogo, '/')) : '') }}"
                                            x-bind:src="frontendLogo.startsWith('http://') || frontendLogo.startsWith('https://') ? frontendLogo : '/storage/' + frontendLogo.replace(/^\/+/, '')"
                                            alt="Frontend logo preview"
                                            class="w-auto object-contain"
                                            x-bind:style="`height:${frontendHeight}px;max-width:${frontendMaxWidth}px;width:auto;`"
                                        >
                                    </template>
                                    <template x-if="!frontendLogo">
                                        <span class="text-sm text-slate-400">No frontend logo set yet</span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div
                            x-data="{
                                adminLogo: @js(old('logo_admin', optional($settings)->logo_admin ?? '')),
                                adminHeight: {{ max(24, min(160, (int) old('logo_admin_height', optional($settings)->logo_admin_height ?? 40))) }},
                                adminMaxWidth: {{ max(60, min(320, (int) old('logo_admin_max_width', optional($settings)->logo_admin_max_width ?? 140))) }},
                                adminAlignment: @js(old('logo_admin_alignment', optional($settings)->logo_admin_alignment ?? 'center'))
                            }"
                            class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4"
                        >
                            <h4 class="text-sm font-semibold text-slate-900">Admin Logo</h4>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-700">PNG logo image</label>
                                <input type="text" name="logo_admin" x-model="adminLogo" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="path/to/admin-logo.png or full URL">
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Logo height</p>
                                        <p class="text-xs text-slate-500">The PNG keeps its proportions automatically.</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700" x-text="adminHeight + 'px'"></span>
                                </div>
                                <input type="range" name="logo_admin_height" x-model="adminHeight" min="24" max="160" step="1" class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-accent">
                                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                    <span>24px</span>
                                    <span>160px</span>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Logo max width</p>
                                        <p class="text-xs text-slate-500">Sets the allowed width without stretching the image.</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700" x-text="adminMaxWidth + 'px'"></span>
                                </div>
                                <input type="range" name="logo_admin_max_width" x-model="adminMaxWidth" min="60" max="320" step="1" class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-accent">
                                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                    <span>60px</span>
                                    <span>320px</span>
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-700">Height value (px)</label>
                                    <input type="number" x-model="adminHeight" min="24" max="160" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-700">Max width value (px)</label>
                                    <input type="number" x-model="adminMaxWidth" min="60" max="320" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                </div>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-700">Alignment</label>
                                <select name="logo_admin_alignment" x-model="adminAlignment" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                    <option value="left" {{ old('logo_admin_alignment', optional($settings)->logo_admin_alignment ?? 'center') === 'left' ? 'selected' : '' }}>Left</option>
                                    <option value="center" {{ old('logo_admin_alignment', optional($settings)->logo_admin_alignment ?? 'center') === 'center' ? 'selected' : '' }}>Center</option>
                                </select>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Preview</p>
                                <div class="flex min-h-[120px] rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 items-center" :class="adminAlignment === 'center' ? 'justify-center' : 'justify-start'">
                                    @php $previewAdminLogo = old('logo_admin', optional($settings)->logo_admin ?? ''); @endphp
                                    <template x-if="adminLogo">
                                        <img
                                            src="{{ (str_starts_with($previewAdminLogo, 'http://') || str_starts_with($previewAdminLogo, 'https://')) ? $previewAdminLogo : ($previewAdminLogo ? asset('storage/' . ltrim($previewAdminLogo, '/')) : '') }}"
                                            x-bind:src="adminLogo.startsWith('http://') || adminLogo.startsWith('https://') ? adminLogo : '/storage/' + adminLogo.replace(/^\/+/, '')"
                                            alt="Admin logo preview"
                                            class="w-auto object-contain"
                                            x-bind:style="`height:${adminHeight}px;max-width:${adminMaxWidth}px;width:auto;`"
                                        >
                                    </template>
                                    <template x-if="!adminLogo">
                                        <span class="text-sm text-slate-400">No admin logo set yet</span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div
                            x-data="{
                                footerLogo: @js(old('logo_footer', optional($settings)->logo_footer ?? '')),
                                footerHeight: {{ max(20, min(120, (int) old('logo_footer_height', optional($settings)->logo_footer_height ?? 40))) }},
                                footerMaxWidth: {{ max(60, min(280, (int) old('logo_footer_max_width', optional($settings)->logo_footer_max_width ?? 160))) }}
                            }"
                            class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50/70 p-4"
                        >
                            <h4 class="text-sm font-semibold text-slate-900">Footer Logo</h4>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-700">PNG logo image</label>
                                <input type="text" name="logo_footer" x-model="footerLogo" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="path/to/footer-logo.png or full URL. Leave empty to use Frontend logo.">
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Logo height</p>
                                        <p class="text-xs text-slate-500">Used in footer (or frontend logo height if no footer logo set).</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700" x-text="footerHeight + 'px'"></span>
                                </div>
                                <input type="range" name="logo_footer_height" x-model="footerHeight" min="20" max="120" step="1" class="logo-size-range h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-accent">
                                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                    <span>20px</span>
                                    <span>120px</span>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">Logo max width</p>
                                        <p class="text-xs text-slate-500">Max width in footer.</p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700" x-text="footerMaxWidth + 'px'"></span>
                                </div>
                                <input type="range" name="logo_footer_max_width" x-model="footerMaxWidth" min="60" max="280" step="1" class="logo-size-range h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-accent">
                                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                    <span>60px</span>
                                    <span>280px</span>
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-700">Height value (px)</label>
                                    <input type="number" x-model.number="footerHeight" min="20" max="120" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-700">Max width value (px)</label>
                                    <input type="number" x-model.number="footerMaxWidth" min="60" max="280" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                </div>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Preview</p>
                                <div class="flex min-h-[100px] rounded-xl border border-dashed border-slate-200 bg-slate-800 px-4 items-center justify-start">
                                    <template x-if="footerLogo">
                                        <img
                                            x-bind:src="footerLogo.startsWith('http://') || footerLogo.startsWith('https://') ? footerLogo : '/storage/' + footerLogo.replace(/^\/+/, '')"
                                            alt="Footer logo preview"
                                            class="w-auto object-contain opacity-90"
                                            x-bind:style="`height:${footerHeight}px;max-width:${footerMaxWidth}px;width:auto;`"
                                        >
                                    </template>
                                    <template x-if="!footerLogo">
                                        <span class="text-sm text-slate-400">No footer logo set → frontend logo will be used</span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="xl:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-slate-700">Favicon</label>
                            <input type="text" name="favicon" value="{{ old('favicon', optional($settings)->favicon ?? '') }}" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="path/to/favicon.ico or full URL">
                        </div>
                    </div>
                </x-ui.card>
            </div>

            {{-- Homepage --}}
            <div x-show="tab === 'homepage'" x-cloak class="space-y-4">
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Homepage Main Image & Hero</h3>
                        <p class="text-sm text-slate-500">The admin manages the main homepage image, the overlay, and all hero texts from this section.</p>
                    </div>
                    <div class="space-y-4">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <h4 class="text-sm font-semibold text-slate-900">Hero images</h4>
                            <p class="mt-1 text-sm text-slate-500">One image (main) or multiple images for a slideshow. Use the single field below for one image, or add one URL/path per line in the textarea for a slideshow.</p>
                            <div class="mt-4 space-y-4">
                                <x-ui.input label="Main image (single)" name="hero_image" :value="old('hero_image', optional($settings)->hero_image ?? '')" placeholder="https://... or path in storage (e.g. hero/cover.jpg)" />
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Multiple hero images (slideshow)</label>
                                    <textarea name="hero_images_text" rows="6" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="One line per image: URL or path (e.g. hero/slide1.jpg)">{{ old('hero_images_text', is_array(optional($settings)->hero_images ?? null) ? implode("\n", optional($settings)->hero_images ?? []) : '') }}</textarea>
                                    <p class="mt-1 text-xs text-slate-500">If you add multiple lines here, the homepage will show a slideshow with these images. Otherwise the single "Main image" above is used.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Slideshow change interval (seconds)</label>
                                    <input type="number" name="hero_slideshow_interval" value="{{ old('hero_slideshow_interval', optional($settings)->hero_slideshow_interval ?? 5) }}" min="2" max="60" step="1" class="block w-24 rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                    <p class="mt-1 text-xs text-slate-500">How many seconds each image is shown (2–60). Default: 5.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Transition duration (seconds)</label>
                                    <input type="number" name="hero_slideshow_transition" value="{{ old('hero_slideshow_transition', optional($settings)->hero_slideshow_transition ?? 1.5) }}" min="0.5" max="5" step="0.5" class="block w-24 rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">
                                    <p class="mt-1 text-xs text-slate-500">How long the fade between images lasts (0.5–5 sec). Higher value = smoother transition. Default: 1.5.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Preview</label>
                                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                        @php
                                            $heroImages = optional($settings)->hero_images ?? [];
                                            $heroImages = is_array($heroImages) ? $heroImages : [];
                                            $heroPreview = $heroImages !== [] ? $heroImages[0] : (old('hero_image', optional($settings)->hero_image ?? ''));
                                        @endphp
                                        @if($heroPreview)
                                            <img
                                                src="{{ (str_starts_with($heroPreview, 'http://') || str_starts_with($heroPreview, 'https://')) ? $heroPreview : asset('storage/' . ltrim($heroPreview, '/')) }}"
                                                alt="Hero preview"
                                                class="h-48 w-full object-cover"
                                            >
                                        @else
                                            <div class="flex h-48 items-center justify-center text-sm text-slate-400">No image selected</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h4 class="text-sm font-semibold text-slate-900">Hero Content</h4>
                            <p class="mt-1 text-sm text-slate-500">Manage the text and highlights that appear above the main image.</p>
                            <div class="mt-4 space-y-4">
                        <x-ui.input label="Hero badge" name="hero_badge" :value="old('hero_badge', optional($settings)->hero_badge ?? '')" placeholder="Official local information" />
                        <x-ui.input label="Hero title" name="hero_title" :value="old('hero_title', optional($settings)->hero_title ?? '')" placeholder="Your gateway to the area" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Hero subtitle</label>
                            <textarea name="hero_subtitle" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('hero_subtitle', optional($settings)->hero_subtitle ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Hero highlight 1" name="hero_highlight_1" :value="old('hero_highlight_1', optional($settings)->hero_highlight_1 ?? '')" placeholder="Informational content about the destination" />
                        <x-ui.input label="Hero highlight 2" name="hero_highlight_2" :value="old('hero_highlight_2', optional($settings)->hero_highlight_2 ?? '')" placeholder="Local businesses & services" />
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-slate-700">Hero overlay (applied to all images)</label>
                            <div class="flex flex-wrap items-center gap-4">
                                <div>
                                    <span class="block text-xs text-slate-500 mb-1">Opacity (0–1)</span>
                                    <input type="text" name="hero_overlay" value="{{ old('hero_overlay', optional($settings)->hero_overlay ?? '0.9') }}" class="block w-24 rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="0.9">
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-500 mb-1">Color</span>
                                    @php $heroOverlayColorVal = old('hero_overlay_color', optional($settings)->hero_overlay_color ?? optional($settings)->user_primary_color ?? optional($settings)->primary_color ?? '#1E3A5F'); @endphp
                                    <div class="flex items-center gap-2">
                                        <input type="color" id="hero_overlay_color_picker" value="{{ preg_match('/^#?[0-9A-Fa-f]{6}$/', trim($heroOverlayColorVal)) ? (str_starts_with(trim($heroOverlayColorVal), '#') ? $heroOverlayColorVal : '#' . $heroOverlayColorVal) : '#1E3A5F' }}" class="h-10 w-14 cursor-pointer rounded border border-slate-300 bg-white p-1" title="Pick color">
                                        <input type="text" name="hero_overlay_color" value="{{ $heroOverlayColorVal }}" class="w-24 rounded-lg border-slate-300 text-sm font-mono focus:border-accent focus:ring-accent" placeholder="#1E3A5F" id="hero_overlay_color_hex" maxlength="7">
                                    </div>
                                    <script>
                                        (function(){
                                            var picker = document.getElementById('hero_overlay_color_picker');
                                            var hex = document.getElementById('hero_overlay_color_hex');
                                            if (!picker || !hex) return;
                                            picker.addEventListener('input', function(){ hex.value = this.value; });
                                            hex.addEventListener('input', function(){ if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) picker.value = this.value; });
                                        })();
                                    </script>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500">The overlay applies to one or multiple images. Choose opacity and color.</p>
                        </div>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            {{-- Content (homepage only: hero blocks, promoted, why, testimonials, CTA) – disabled now, managed via translations --}}
            <div x-show="false" x-cloak class="space-y-4">
                <x-ui.card class="border-accent/30 bg-slate-50/50">
                    <p class="text-sm text-slate-600">This tab is only for <strong>homepage</strong> content (sections that appear on the main page). For texts of other pages (About, Places, Businesses, Contact, etc.) use the <strong>Page content</strong> tab.</p>
                </x-ui.card>
                <x-ui.card>
                    <h3 class="text-sm font-semibold text-primary mb-4">Promoted businesses section (homepage)</h3>
                    <div class="space-y-4">
                        <x-ui.input label="Title" name="promoted_businesses_title" :value="old('promoted_businesses_title', optional($settings)->promoted_businesses_title ?? '')" placeholder="Promoted local businesses" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Subtitle</label>
                            <textarea name="promoted_businesses_subtitle" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('promoted_businesses_subtitle', optional($settings)->promoted_businesses_subtitle ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <h3 class="text-sm font-semibold text-primary mb-4">Why section</h3>
                    <div class="space-y-4">
                        <x-ui.input label="Title" name="why_title" :value="old('why_title', optional($settings)->why_title ?? '')" placeholder="How this guide helps you" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Intro</label>
                            <textarea name="why_intro" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('why_intro', optional($settings)->why_intro ?? '') }}</textarea>
                        </div>
                        @foreach([1, 2, 3] as $i)
                        <div class="border-t border-slate-100 pt-4 space-y-2">
                            <x-ui.input label="Point {{ $i }} title" name="why_point{{ $i }}_title" :value="old('why_point'.$i.'_title', data_get($settings ?? null, 'why_point'.$i.'_title', ''))" />
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Point {{ $i }} body</label>
                                <textarea name="why_point{{ $i }}_body" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('why_point'.$i.'_body', data_get($settings ?? null, 'why_point'.$i.'_body', '')) }}</textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <h3 class="text-sm font-semibold text-primary mb-4">Testimonials section</h3>
                    <div class="space-y-4">
                        <x-ui.input label="Badge" name="testimonials_badge" :value="old('testimonials_badge', optional($settings)->testimonials_badge ?? '')" placeholder="Guest stories" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Quote</label>
                            <textarea name="testimonials_quote" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('testimonials_quote', optional($settings)->testimonials_quote ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Author name" name="testimonials_name" :value="old('testimonials_name', optional($settings)->testimonials_name ?? '')" placeholder="Anna & Markus, Germany" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Note (below quote)</label>
                            <textarea name="testimonials_note" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('testimonials_note', optional($settings)->testimonials_note ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <h3 class="text-sm font-semibold text-primary mb-4">CTA section</h3>
                    <div class="space-y-4">
                        <x-ui.input label="Title" name="cta_title" :value="old('cta_title', optional($settings)->cta_title ?? '')" placeholder="Want to develop this portal further?" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Subtitle</label>
                            <textarea name="cta_subtitle" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('cta_subtitle', optional($settings)->cta_subtitle ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Primary button text" name="cta_primary" :value="old('cta_primary', optional($settings)->cta_primary ?? '')" placeholder="Get in touch" />
                        <x-ui.input label="Secondary button text" name="cta_secondary" :value="old('cta_secondary', optional($settings)->cta_secondary ?? '')" placeholder="View local businesses" />
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <h3 class="text-sm font-semibold text-primary mb-4">Contact teaser (below CTA)</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Text</label>
                            <textarea name="contact_teaser" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('contact_teaser', optional($settings)->contact_teaser ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            {{-- Page content (CMS) – one section per frontend page – disabled now, managed via translations --}}
            <div x-show="false" x-cloak class="space-y-6">
                <x-ui.card class="border-accent/30 bg-slate-50/50">
                    <p class="text-sm text-slate-600">Each block below is for <strong>one frontend page</strong>. The heading shows the page name and URL so you know exactly where the text appears.</p>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Home page – Featured places block</h3>
                        <p class="text-sm text-slate-500">Title and subtitle for the featured places block on the homepage (/).</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="Featured places title" name="featured_places_title" :value="old('featured_places_title', optional($settings)->featured_places_title ?? '')" :placeholder="__('messages.cms.featured_places_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Featured places subtitle</label>
                            <textarea name="featured_places_subtitle" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.featured_places_subtitle') }}">{{ old('featured_places_subtitle', optional($settings)->featured_places_subtitle ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">About page <span class="font-mono text-xs font-normal text-slate-500">/about</span></h3>
                        <p class="text-sm text-slate-500">Main title, intro and three content sections. These texts appear only on the About page.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="About page title" name="about_title" :value="old('about_title', optional($settings)->about_title ?? '')" :placeholder="__('messages.cms.about_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">About intro</label>
                            <textarea name="about_intro" rows="3" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.about_intro') }}">{{ old('about_intro', optional($settings)->about_intro ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Section 1 title" name="about_section1_title" :value="old('about_section1_title', optional($settings)->about_section1_title ?? '')" :placeholder="__('messages.cms.about_section1_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Section 1 body</label>
                            <textarea name="about_section1_body" rows="3" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.about_section1_body') }}">{{ old('about_section1_body', optional($settings)->about_section1_body ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Section 2 title" name="about_section2_title" :value="old('about_section2_title', optional($settings)->about_section2_title ?? '')" :placeholder="__('messages.cms.about_section2_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Section 2 body</label>
                            <textarea name="about_section2_body" rows="3" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.about_section2_body') }}">{{ old('about_section2_body', optional($settings)->about_section2_body ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Section 3 title" name="about_section3_title" :value="old('about_section3_title', optional($settings)->about_section3_title ?? '')" :placeholder="__('messages.cms.about_section3_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Section 3 body</label>
                            <textarea name="about_section3_body" rows="3" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.about_section3_body') }}">{{ old('about_section3_body', optional($settings)->about_section3_body ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Places page <span class="font-mono text-xs font-normal text-slate-500">/places</span></h3>
                        <p class="text-sm text-slate-500">Title, subtitle and intro for the Places listing page. Only this page uses these fields.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="Places page title" name="places_title" :value="old('places_title', optional($settings)->places_title ?? '')" placeholder="Places to Visit" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Places page subtitle</label>
                            <textarea name="places_subtitle" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('places_subtitle', optional($settings)->places_subtitle ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Places intro</label>
                            <textarea name="places_intro" rows="4" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.places_intro') }}">{{ old('places_intro', optional($settings)->places_intro ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Discover page – Activities & Beaches <span class="font-mono text-xs font-normal text-slate-500">/discover</span></h3>
                        <p class="text-sm text-slate-500">Titles and intros for the Activities and Beaches sections on the Discover page only.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="Activities section title" name="activities_title" :value="old('activities_title', optional($settings)->activities_title ?? '')" :placeholder="__('messages.cms.activities_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Activities intro</label>
                            <textarea name="activities_intro" rows="4" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.activities_intro') }}">{{ old('activities_intro', optional($settings)->activities_intro ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Beaches section title" name="beaches_title" :value="old('beaches_title', optional($settings)->beaches_title ?? '')" :placeholder="__('messages.cms.beaches_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Beaches intro</label>
                            <textarea name="beaches_intro" rows="4" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.beaches_intro') }}">{{ old('beaches_intro', optional($settings)->beaches_intro ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Businesses page <span class="font-mono text-xs font-normal text-slate-500">/businesses</span></h3>
                        <p class="text-sm text-slate-500">Title, subtitle and intro for the Local businesses listing page. Only this page uses these fields.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="Businesses page title" name="businesses_title" :value="old('businesses_title', optional($settings)->businesses_title ?? '')" placeholder="Promoted local businesses" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Businesses page subtitle</label>
                            <textarea name="businesses_subtitle" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent">{{ old('businesses_subtitle', optional($settings)->businesses_subtitle ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Businesses intro</label>
                            <textarea name="businesses_intro" rows="4" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.businesses_intro') }}">{{ old('businesses_intro', optional($settings)->businesses_intro ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">On the map page <span class="font-mono text-xs font-normal text-slate-500">/on-the-map</span></h3>
                        <p class="text-sm text-slate-500">Title and subtitle for the map page only.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="On the map page title" name="on_map_title" :value="old('on_map_title', optional($settings)->on_map_title ?? '')" placeholder="On the map" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">On the map page subtitle</label>
                            <textarea name="on_map_subtitle" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="Businesses with a location set. Click a pin or a card to see details.">{{ old('on_map_subtitle', optional($settings)->on_map_subtitle ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Discover page – Accommodation & Food <span class="font-mono text-xs font-normal text-slate-500">/discover</span></h3>
                        <p class="text-sm text-slate-500">Titles and intros for Accommodation and Food sections on the Discover page only.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="Accommodation section title" name="accommodation_title" :value="old('accommodation_title', optional($settings)->accommodation_title ?? '')" :placeholder="__('messages.cms.accommodation_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Accommodation intro</label>
                            <textarea name="accommodation_intro" rows="4" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.accommodation_intro') }}">{{ old('accommodation_intro', optional($settings)->accommodation_intro ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Food & restaurants section title" name="food_title" :value="old('food_title', optional($settings)->food_title ?? '')" :placeholder="__('messages.cms.food_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Food intro</label>
                            <textarea name="food_intro" rows="4" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.food_intro') }}">{{ old('food_intro', optional($settings)->food_intro ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Contact page <span class="font-mono text-xs font-normal text-slate-500">/contact</span></h3>
                        <p class="text-sm text-slate-500">Title, subtitle and intro for the Contact page only.</p>
                    </div>
                    <div class="space-y-4">
                        <x-ui.input label="Contact page title" name="contact_title" :value="old('contact_title', optional($settings)->contact_title ?? '')" :placeholder="__('messages.cms.contact_title')" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Contact subtitle</label>
                            <textarea name="contact_subtitle" rows="2" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.contact_subtitle') }}">{{ old('contact_subtitle', optional($settings)->contact_subtitle ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Contact intro</label>
                            <textarea name="contact_intro" rows="3" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="{{ __('messages.cms.contact_intro') }}">{{ old('contact_intro', optional($settings)->contact_intro ?? '') }}</textarea>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            {{-- Legal --}}
            <div x-show="tab === 'legal'" x-cloak class="space-y-4">
                <x-ui.card>
                    <div class="mb-4 space-y-1">
                        <h3 class="text-base font-semibold text-primary">Privacy policy & Terms of use</h3>
                        <p class="text-sm text-slate-500">Edit the content of the Privacy policy and Terms of use pages (EN &amp; EL). These appear as links in the footer.</p>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-slate-800 mb-1">Privacy policy (EN)</label>
                            <textarea name="privacy_policy" id="privacy_policy_editor" rows="10" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent font-mono" placeholder="<p>Your privacy policy text here. HTML is supported.</p>">{{ old('privacy_policy', optional($settings)->privacy_policy ?? '') }}</textarea>
                            <label class="block text-sm font-semibold text-slate-800 mb-1 mt-4">Privacy policy (ΕΛ)</label>
                            <textarea name="privacy_policy_el" id="privacy_policy_el_editor" rows="10" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent font-mono" placeholder="<p>Κείμενο πολιτικής απορρήτου στα ελληνικά. Υποστηρίζεται HTML.</p>">{{ old('privacy_policy_el', optional($settings)->privacy_policy_el ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-slate-500">Shown on the /privacy-policy page. The version in the current language is used if available.</p>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-slate-800 mb-1">Terms of use (EN)</label>
                            <textarea name="terms_of_use" id="terms_of_use_editor" rows="10" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent font-mono" placeholder="<p>Your terms of use text here. HTML is supported.</p>">{{ old('terms_of_use', optional($settings)->terms_of_use ?? '') }}</textarea>
                            <label class="block text-sm font-semibold text-slate-800 mb-1 mt-4">Terms of use (ΕΛ)</label>
                            <textarea name="terms_of_use_el" id="terms_of_use_el_editor" rows="10" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent font-mono" placeholder="<p>Κείμενο όρων χρήσης στα ελληνικά. Υποστηρίζεται HTML.</p>">{{ old('terms_of_use_el', optional($settings)->terms_of_use_el ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-slate-500">Shown on the /terms-of-use page. The version in the current language is used if available.</p>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            {{-- Navigation & Footer – disabled now, labels and footer text use translations --}}
            <div x-show="false" x-cloak class="space-y-4">
                <x-ui.card>
                    <h3 class="text-sm font-semibold text-primary mb-4">Menu / Navigation labels</h3>
                    <p class="text-xs text-slate-500 mb-4">Labels shown in the main menu and footer links. Leave empty to use the default text.</p>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.input label="Home" name="nav_home" :value="old('nav_home', optional($settings)->nav_home ?? '')" placeholder="Home" />
                        <x-ui.input label="Places to visit" name="nav_places_to_visit" :value="old('nav_places_to_visit', optional($settings)->nav_places_to_visit ?? '')" placeholder="Places to Visit" />
                        <x-ui.input label="Local businesses" name="nav_businesses" :value="old('nav_businesses', optional($settings)->nav_businesses ?? '')" placeholder="Local Businesses" />
                        <x-ui.input label="On the map (menu label)" name="nav_on_map" :value="old('nav_on_map', optional($settings)->nav_on_map ?? '')" placeholder="On the map" />
                        <x-ui.input label="About" name="nav_about" :value="old('nav_about', optional($settings)->nav_about ?? '')" placeholder="About" />
                        <x-ui.input label="Contact" name="nav_contact" :value="old('nav_contact', optional($settings)->nav_contact ?? '')" placeholder="Contact" />
                    </div>
                </x-ui.card>
                <x-ui.card>
                    <h3 class="text-sm font-semibold text-primary mb-4">Footer content</h3>
                    <p class="text-xs text-slate-500 mb-4">Text shown in the footer (under the logo and column titles). Leave empty to use defaults.</p>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Footer about text (main description under logo)</label>
                            <textarea name="footer_content" rows="4" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="Visit Sunset Bay is the official inspiration and information portal for exploring our coastal region, islands and hidden inland villages.">{{ old('footer_content', optional($settings)->footer_content ?? '') }}</textarea>
                        </div>
                        <x-ui.input label="Footer 'Explore' section title" name="footer_explore" :value="old('footer_explore', optional($settings)->footer_explore ?? '')" placeholder="Explore" />
                        <x-ui.input label="Footer 'Connect' section title" name="footer_connect" :value="old('footer_connect', optional($settings)->footer_connect ?? '')" placeholder="Connect" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Footer rights text</label>
                            <input type="text" name="footer_rights" value="{{ old('footer_rights', optional($settings)->footer_rights ?? '') }}" class="block w-full rounded-lg border-slate-300 text-sm focus:border-accent focus:ring-accent" placeholder="All rights reserved.">
                        </div>
                    </div>
                </x-ui.card>
            </div>

            {{-- Contact --}}
            <div x-show="tab === 'contact'" x-cloak class="space-y-4">
                <x-ui.card>
                    <h3 class="text-sm font-semibold text-primary mb-4">Contact details</h3>
                    <p class="text-xs text-slate-500 mb-4">Email and phone shown on the Contact page and in the footer. Social links appear in the footer.</p>
                    <div class="space-y-4">
                        <x-ui.input label="Contact email" name="contact_email" type="email" :value="old('contact_email', optional($settings)->contact_email ?? '')" placeholder="info@example.com" :error="$errors->first('contact_email')" />
                        <x-ui.input label="Contact phone" name="contact_phone" :value="old('contact_phone', optional($settings)->contact_phone ?? '')" placeholder="+30 ..." />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Social links (JSON)</label>
                            <textarea name="social_links_json" rows="3" class="block w-full rounded-lg border-slate-300 text-sm font-mono focus:border-accent focus:ring-accent" placeholder='{"facebook":"https://...","instagram":"https://..."}'>{{ old('social_links_json', is_array(optional($settings)->social_links ?? null) ? json_encode(optional($settings)->social_links, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
                            <p class="mt-1 text-xs text-slate-500">Enter JSON object with keys: facebook, instagram, youtube, etc.</p>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <div class="flex gap-2">
                <x-ui.button type="submit" variant="primary">Save settings</x-ui.button>
            </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    (function() {
        function initLegalEditors() {
            if (typeof window.tinymce === 'undefined') return;
            try {
                window.tinymce.init({
                    selector: '#privacy_policy_editor',
                    height: 320,
                    menubar: false,
                    plugins: 'lists link code',
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link | removeformat code',
                    content_style: 'body { font-family: system-ui, sans-serif; font-size: 14px; }',
                    branding: false,
                });
                window.tinymce.init({
                    selector: '#terms_of_use_editor',
                    height: 320,
                    menubar: false,
                    plugins: 'lists link code',
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link | removeformat code',
                    content_style: 'body { font-family: system-ui, sans-serif; font-size: 14px; }',
                    branding: false,
                });
            } catch (e) { console.warn('TinyMCE init:', e); }
        }
        var s = document.querySelector('script[src*="tinymce"]');
        if (s) s.addEventListener('load', initLegalEditors);
        else if (document.readyState === 'complete') initLegalEditors();
        else window.addEventListener('load', initLegalEditors);
    })();
    </script>
@endsection
