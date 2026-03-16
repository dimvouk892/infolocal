<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    public function index(Request $request): View
    {
        $disk = Storage::disk('public');
        $folder = $request->input('folder', '');
        $search = $request->input('search', '');
        $perPage = 24;

        $allFiles = $this->scanImages($disk, $folder);
        $folders = $this->getFolders($disk, $folder);

        if ($search !== '') {
            $allFiles = $allFiles->filter(fn ($f) => stripos($f['filename'], $search) !== false);
        }

        $images = $allFiles->forPage($request->input('page', 1), $perPage)->values();
        $total = $allFiles->count();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $images,
            $total,
            $perPage,
            $request->input('page', 1),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $parentFolder = $folder !== '' ? dirname($folder) : '';
        if ($parentFolder === '.') {
            $parentFolder = '';
        }

        $allFoldersForSelect = $this->getAllFoldersRecursive($disk);

        return view('admin.media.index', [
            'images' => $paginator,
            'folders' => $folders,
            'currentFolder' => $folder,
            'parentFolder' => $parentFolder,
            'search' => $search,
            'allFoldersForSelect' => $allFoldersForSelect,
            'picker' => $request->boolean('picker'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,svg', 'max:10240'],
            'folder' => ['nullable', 'string', 'max:500'],
        ]);

        $folder = $this->sanitizePath(trim((string) $request->input('folder', ''), '/'));
        $directory = $folder !== '' ? $folder : 'media';

        $file = $request->file('image');
        $name = uniqid('', true) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs($directory, $name, 'public');

        return redirect()
            ->route('admin.media.index', ['folder' => $folder ?: null])
            ->with('success', __('Image uploaded.'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $path = $request->input('path');
        if (empty($path) || ! is_string($path)) {
            return back()->with('error', __('Invalid path.'));
        }

        $path = $this->sanitizePath($path);
        if (! Storage::disk('public')->exists($path)) {
            return back()->with('error', __('File not found.'));
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, self::IMAGE_EXTENSIONS, true)) {
            return back()->with('error', __('Invalid file type.'));
        }

        Storage::disk('public')->delete($path);

        return back()->with('success', __('Image deleted.'));
    }

    public function moveImage(Request $request): RedirectResponse
    {
        $request->validate([
            'path' => ['required', 'string', 'max:500'],
            'destination' => ['required', 'string', 'max:500'],
        ]);

        $path = $this->sanitizePath($request->input('path'));
        $destination = $this->sanitizePath(trim($request->input('destination'), '/'));
        if (str_contains($path, '..') || str_contains($destination, '..')) {
            return back()->with('error', __('Invalid path.'));
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return back()->with('error', __('File not found.'));
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, self::IMAGE_EXTENSIONS, true)) {
            return back()->with('error', __('Invalid file type.'));
        }

        $destDir = $destination !== '' ? $destination : 'media';
        $newPath = $destDir . '/' . basename($path);
        if ($newPath === $path) {
            return back()->with('info', __('Image is already in this folder.'));
        }
        if ($disk->exists($newPath)) {
            return back()->with('error', __('A file with this name already exists in the destination folder.'));
        }

        if ($destDir !== '' && ! $disk->exists($destDir)) {
            $disk->makeDirectory($destDir);
        }

        $disk->move($path, $newPath);

        return redirect()
            ->route('admin.media.index', ['folder' => $request->input('current_folder') ?: null])
            ->with('success', __('Image moved.'));
    }

    public function download(Request $request): StreamedResponse
    {
        $path = $request->input('path');
        if (empty($path) || ! is_string($path)) {
            abort(404);
        }

        $path = $this->sanitizePath($path);
        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $filename = basename($path);

        return Storage::disk('public')->download($path, $filename);
    }

    public function createFolder(Request $request): RedirectResponse
    {
        $request->validate([
            'folder' => ['nullable', 'string', 'max:500'],
            'name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9._-]+$/'],
        ]);

        $parent = trim((string) $request->input('folder', ''), '/');
        $name = trim($request->input('name'));
        $newPath = $parent !== '' ? $parent . '/' . $name : $name;

        $newPath = $this->sanitizePath($newPath);
        if (str_contains($newPath, '..')) {
            return back()->with('error', __('Invalid path.'));
        }

        $disk = Storage::disk('public');
        if ($disk->exists($newPath)) {
            return back()->with('error', __('A folder with this name already exists.'));
        }

        $disk->makeDirectory($newPath);

        return redirect()
            ->route('admin.media.index', ['folder' => $parent ?: null])
            ->with('success', __('Folder created.'));
    }

    public function renameFolder(Request $request): RedirectResponse
    {
        $request->validate([
            'path' => ['required', 'string', 'max:500'],
            'new_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9._-]+$/'],
        ]);

        $path = $this->sanitizePath($request->input('path'));
        $newName = trim($request->input('new_name'));
        if (str_contains($path, '..') || $newName === '' || $newName === '.' || $newName === '..') {
            return back()->with('error', __('Invalid path or name.'));
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return back()->with('error', __('Folder not found.'));
        }

        $parent = dirname($path);
        $newPath = ($parent !== '.' ? $parent . '/' : '') . $newName;
        if ($disk->exists($newPath)) {
            return back()->with('error', __('A folder with this name already exists.'));
        }

        $disk->move($path, $newPath);

        $currentFolder = $request->input('current_folder', '');
        $redirectFolder = $currentFolder;
        if ($currentFolder === $path || str_starts_with($currentFolder . '/', $path . '/')) {
            $redirectFolder = $newPath . (str_starts_with($currentFolder . '/', $path . '/') ? substr($currentFolder, strlen($path)) : '');
        }

        return redirect()
            ->route('admin.media.index', ['folder' => $redirectFolder ?: null])
            ->with('success', __('Folder renamed.'));
    }

    public function destroyFolder(Request $request): RedirectResponse
    {
        $path = $request->input('path');
        if (empty($path) || ! is_string($path)) {
            return back()->with('error', __('Invalid path.'));
        }

        $path = rtrim($this->sanitizePath($path), '/');
        if (str_contains($path, '..')) {
            return back()->with('error', __('Invalid path.'));
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return back()->with('error', __('Folder not found.'));
        }

        $fullPath = rtrim($disk->path($path), DIRECTORY_SEPARATOR);
        if (! is_dir($fullPath)) {
            return back()->with('error', __('Not a directory.'));
        }

        try {
            if (! File::deleteDirectory($fullPath)) {
                return back()->with('error', __('Could not delete folder.'));
            }
        } catch (\Throwable $e) {
            return back()->with('error', __('Could not delete folder.') . ' ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.media.index', ['folder' => dirname($path) !== '.' ? dirname($path) : null])
            ->with('success', __('Folder deleted.'));
    }

    /** @return \Illuminate\Support\Collection<int, array{path: string, filename: string, size: int, url: string}> */
    private function scanImages($disk, string $folder): \Illuminate\Support\Collection
    {
        $prefix = $folder !== '' ? rtrim($folder, '/') . '/' : '';
        $files = $disk->allFiles($prefix ?: '');

        $images = collect($files)->filter(function ($path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            return in_array($ext, self::IMAGE_EXTENSIONS, true);
        })->map(function ($path) use ($disk) {
            $size = $disk->size($path);
            return [
                'path' => $path,
                'filename' => basename($path),
                'size' => $size,
                'url' => $disk->url($path),
            ];
        })->sortBy('filename')->values();

        return $images;
    }

    /** @return array<string> */
    private function getFolders($disk, string $parent): array
    {
        $prefix = $parent !== '' ? rtrim($parent, '/') . '/' : '';
        $directories = $disk->directories($prefix ?: '');
        $folders = [];
        foreach ($directories as $dir) {
            $name = basename($dir);
            if ($name === '.' || $name === '..') {
                continue;
            }
            $folders[] = $dir;
        }
        sort($folders);
        return $folders;
    }

    /** @return array<string> */
    private function getAllFoldersRecursive($disk): array
    {
        $list = [];
        $this->collectFoldersRecursive($disk, '', $list);
        sort($list);
        return $list;
    }

    /** @param array<string> $list */
    private function collectFoldersRecursive($disk, string $prefix, array &$list): void
    {
        $directories = $disk->directories($prefix);
        foreach ($directories as $dir) {
            $list[] = $dir;
            $this->collectFoldersRecursive($disk, $dir, $list);
        }
    }

    private function sanitizePath(string $path): string
    {
        $path = str_replace(['../', '..\\'], '', $path);
        return ltrim($path, '/');
    }
}
