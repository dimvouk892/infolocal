@extends('layouts.admin')

@section('title', 'Media Library')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">Admin / Media</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Media Library</h1>
                <p class="mt-1 text-sm text-slate-500">@if($picker ?? false) Select an image to use it. @else Manage all images in storage. @endif</p>
            </div>
        </div>

        @if(!($picker ?? false))
        {{-- Upload & filters --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="flex flex-wrap items-end gap-4">
                    <div class="min-w-[200px] flex-1">
                        <label for="image" class="block text-sm font-medium text-slate-700">Upload image</label>
                        <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/*"
                               class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-700">
                        @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="min-w-[200px]">
                        <label for="upload_folder" class="block text-sm font-medium text-slate-700">Save to folder</label>
                        <select id="upload_folder" name="folder" class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="media" {{ ($currentFolder ?: 'media') === 'media' ? 'selected' : '' }}>media (root)</option>
                            @foreach($allFoldersForSelect ?? [] as $f)
                                <option value="{{ $f }}" {{ ($currentFolder ?: '') === $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Choose where to save the image.</p>
                    </div>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        Upload
                    </button>
                </div>
            </form>

            <form method="GET" action="{{ route('admin.media.index') }}" class="mt-6 flex flex-wrap items-center gap-3">
                @if($picker ?? false)<input type="hidden" name="picker" value="1">@endif
                @if($currentFolder)
                    <input type="hidden" name="folder" value="{{ $currentFolder }}">
                @endif
                <div class="flex items-center gap-2">
                    <label for="search" class="text-sm font-medium text-slate-700">Search</label>
                    <input id="search" type="text" name="search" value="{{ $search }}" placeholder="filename..."
                           class="rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <button type="submit" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Filter
                </button>
                @if($search || $currentFolder)
                    <a href="{{ route('admin.media.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Clear</a>
                @endif
            </form>
        </section>
        @else
        {{-- Picker: only search --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.media.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="picker" value="1">
                @if($currentFolder)<input type="hidden" name="folder" value="{{ $currentFolder }}">@endif
                <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Search by filename..." class="rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Search</button>
                @if($search)<a href="{{ route('admin.media.index', array_filter(['picker' => 1, 'folder' => $currentFolder ?: null])) }}" class="text-sm text-slate-500 hover:text-slate-700">Clear</a>@endif
            </form>
        </section>
        @endif

        {{-- Folder navigation --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span class="text-sm font-semibold text-slate-800">Path / Subfolders</span>
                </div>
                <form method="POST" action="{{ route('admin.media.folders.create') }}" class="flex items-center gap-2">
                    @csrf
                    <input type="hidden" name="folder" value="{{ $currentFolder ?? '' }}">
                    <input type="text" name="name" required maxlength="100" pattern="[a-zA-Z0-9._-]+" placeholder="New folder name" title="Letters, numbers, . _ - only"
                           class="rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                        New folder
                    </button>
                </form>
            </div>
            {{-- Breadcrumb --}}
            <div class="mb-4 flex flex-wrap items-center gap-1 text-sm">
                <a href="{{ route('admin.media.index', array_filter(['search' => $search, 'picker' => ($picker ?? false) ? 1 : null])) }}"
                   class="rounded-md px-2 py-1 {{ !$currentFolder ? 'bg-emerald-100 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    storage
                </a>
                @if($currentFolder)
                    @php $parts = explode('/', $currentFolder); $acc = ''; @endphp
                    @foreach($parts as $i => $part)
                        @php $acc = $acc ? $acc . '/' . $part : $part; @endphp
                        <span class="text-slate-400">/</span>
                        <a href="{{ route('admin.media.index', array_filter(['folder' => $acc, 'search' => $search, 'picker' => ($picker ?? false) ? 1 : null])) }}"
                           class="rounded-md px-2 py-1 {{ $i === count($parts) - 1 ? 'bg-emerald-100 font-medium text-emerald-800' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            {{ $part }}
                        </a>
                    @endforeach
                @endif
            </div>
            {{-- Subfolders --}}
            @if(count($folders) > 0)
                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-slate-500">Subfolders (click to open, rename / delete)</p>
                <div class="grid gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach($folders as $f)
                        <div class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 transition hover:border-emerald-300 hover:bg-emerald-50/50 {{ $currentFolder === $f ? 'border-emerald-300 bg-emerald-50/80' : '' }}">
                            <a href="{{ route('admin.media.index', array_filter(['folder' => $f, 'search' => $search, 'picker' => ($picker ?? false) ? 1 : null])) }}" class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-slate-500 shadow-sm">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                </span>
                                <span class="min-w-0 truncate font-medium text-slate-800">{{ basename($f) }}</span>
                            </a>
                            <div class="flex shrink-0 items-center gap-1 opacity-0 transition group-hover:opacity-100">
                                <button type="button" class="rename-folder rounded p-1.5 text-slate-500 hover:bg-white hover:text-emerald-600" title="Rename" data-path="{{ $f }}" data-name="{{ basename($f) }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.media.folders.destroy.post') }}" class="inline" onsubmit="return confirm('The folder \"{{ basename($f) }}\" and all its contents will be deleted. Continue?');">
                                    @csrf
                                    <input type="hidden" name="path" value="{{ $f }}">
                                    <button type="submit" class="rounded p-1.5 text-slate-500 hover:bg-white hover:text-red-600" title="Delete folder and contents">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500">No subfolders in this path. Use "New folder" to create one.</p>
            @endif
        </section>

        {{-- Move image modal --}}
        <div id="move-image-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Move image</h3>
                <form id="move-image-form" method="POST" action="{{ route('admin.media.move') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="path" id="move-image-path">
                    <input type="hidden" name="current_folder" value="{{ $currentFolder ?? '' }}">
                    <div>
                        <label for="move-image-destination" class="block text-sm font-medium text-slate-700">Destination (folder)</label>
                        <select name="destination" id="move-image-destination" required class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="media">media (root)</option>
                            @foreach($allFoldersForSelect ?? [] as $f)
                                <option value="{{ $f }}">{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" id="move-image-cancel" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Move</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Rename folder modal --}}
        <div id="rename-folder-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" role="dialog" aria-modal="true">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Rename folder</h3>
                <form id="rename-folder-form" method="POST" action="{{ route('admin.media.folders.rename') }}" class="mt-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="path" id="rename-folder-path">
                    <input type="hidden" name="current_folder" value="{{ $currentFolder ?? '' }}">
                    <div>
                        <label for="rename-folder-new-name" class="block text-sm font-medium text-slate-700">New name</label>
                        <input type="text" name="new_name" id="rename-folder-new-name" required maxlength="100" pattern="[a-zA-Z0-9._-]+" class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" id="rename-folder-cancel" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Images grid --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @if($images->isEmpty())
                <p class="py-12 text-center text-slate-500">No images found{{ $currentFolder ? ' in this folder' : '' }}.</p>
            @else
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                    @foreach($images as $img)
                        <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition hover:border-emerald-300 hover:shadow-md">
                            <div class="aspect-square overflow-hidden bg-slate-100">
                                <img src="{{ url('storage/' . $img['path']) }}" alt="{{ $img['filename'] }}"
                                     class="h-full w-full object-cover" loading="lazy"
                                     onerror="this.src='{{ asset('images/placeholder.svg') }}'">
                            </div>
                            <div class="p-3">
                                <p class="truncate text-sm font-medium text-slate-800" title="{{ $img['filename'] }}">{{ $img['filename'] }}</p>
                                <p class="text-xs text-slate-500">{{ $img['path'] }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ number_format($img['size'] / 1024, 1) }} KB</p>
                            </div>
                            <div class="absolute right-2 top-2 flex gap-1 opacity-0 transition group-hover:opacity-100">
                                @if($picker ?? false)
                                    <button type="button" class="select-from-media rounded-full bg-emerald-500 p-2 text-white shadow hover:bg-emerald-600"
                                            data-path="{{ $img['path'] }}"
                                            data-url="{{ url('storage/' . $img['path']) }}"
                                            title="Select">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                @else
                                <button type="button" class="copy-path rounded-full bg-white/95 p-1.5 text-slate-600 shadow hover:bg-emerald-500 hover:text-white"
                                        data-path="{{ $img['path'] }}"
                                        title="Copy path (for Place/Business)">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </button>
                                <button type="button" class="copy-url rounded-full bg-white/95 p-1.5 text-slate-600 shadow hover:bg-emerald-500 hover:text-white"
                                        data-url="{{ url('storage/' . $img['path']) }}"
                                        title="Copy URL">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                                <button type="button" class="move-image rounded-full bg-white/95 p-1.5 text-slate-600 shadow hover:bg-emerald-500 hover:text-white"
                                        data-path="{{ $img['path'] }}"
                                        title="Move to another folder">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                </button>
                                <a href="{{ route('admin.media.download', ['path' => $img['path']]) }}"
                                   class="rounded-full bg-white/95 p-1.5 text-slate-600 shadow hover:bg-emerald-500 hover:text-white"
                                   title="Download">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.media.destroy') }}" class="inline" onsubmit="return confirm('Delete this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="path" value="{{ $img['path'] }}">
                                    <button type="submit" class="rounded-full bg-white/95 p-1.5 text-slate-600 shadow hover:bg-red-500 hover:text-white" title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($images->hasPages())
                    <div class="mt-6">
                        {{ $images->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </section>
    </div>

    <script>
        function copyAndFeedback(btn, text) {
            navigator.clipboard.writeText(text).then(function() {
                var orig = btn.innerHTML;
                btn.innerHTML = '<svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                setTimeout(function() { btn.innerHTML = orig; }, 1500);
            });
        }
        document.querySelectorAll('.copy-url').forEach(function(btn) {
            btn.addEventListener('click', function() { copyAndFeedback(this, this.getAttribute('data-url')); });
        });
        document.querySelectorAll('.copy-path').forEach(function(btn) {
            btn.addEventListener('click', function() { copyAndFeedback(this, this.getAttribute('data-path')); });
        });

        var renameModal = document.getElementById('rename-folder-modal');
        var renameForm = document.getElementById('rename-folder-form');
        var renamePath = document.getElementById('rename-folder-path');
        var renameNewName = document.getElementById('rename-folder-new-name');
        var renameCancel = document.getElementById('rename-folder-cancel');
        function showRenameModal(path, currentName) {
            renamePath.value = path;
            renameNewName.value = currentName;
            renameModal.classList.remove('hidden');
            renameModal.classList.add('flex');
            renameNewName.focus();
        }
        function hideRenameModal() {
            renameModal.classList.add('hidden');
            renameModal.classList.remove('flex');
        }
        document.querySelectorAll('.rename-folder').forEach(function(btn) {
            btn.addEventListener('click', function(e) { e.preventDefault(); showRenameModal(this.getAttribute('data-path'), this.getAttribute('data-name')); });
        });
        if (renameCancel) renameCancel.addEventListener('click', hideRenameModal);
        renameModal.addEventListener('click', function(e) { if (e.target === renameModal) hideRenameModal(); });

        var moveModal = document.getElementById('move-image-modal');
        var movePathInput = document.getElementById('move-image-path');
        var moveCancel = document.getElementById('move-image-cancel');
        function showMoveModal(path) {
            movePathInput.value = path;
            moveModal.classList.remove('hidden');
            moveModal.classList.add('flex');
        }
        function hideMoveModal() {
            moveModal.classList.add('hidden');
            moveModal.classList.remove('flex');
        }
        document.querySelectorAll('.move-image').forEach(function(btn) {
            btn.addEventListener('click', function(e) { e.preventDefault(); showMoveModal(this.getAttribute('data-path')); });
        });
        if (moveCancel) moveCancel.addEventListener('click', hideMoveModal);
        moveModal.addEventListener('click', function(e) { if (e.target === moveModal) hideMoveModal(); });

        @if($picker ?? false)
        document.querySelectorAll('.select-from-media').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var path = this.getAttribute('data-path');
                var url = this.getAttribute('data-url');
                if (window.opener) {
                    try {
                        window.opener.postMessage({ type: 'media-picked', path: path, url: url }, window.location.origin);
                    } catch (err) {}
                    window.close();
                } else {
                    copyAndFeedback(this, path);
                }
            });
        });
        @endif
    </script>
@endsection
