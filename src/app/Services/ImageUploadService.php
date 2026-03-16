<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    private const ALLOWED_MIME = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
    ];

    private const ALLOWED_EXT = [
        'jpg',
        'jpeg',
        'png',
        'webp',
    ];

    private const MAX_SIZE_MB = 2;

    public static function maxSizeBytes(): int
    {
        return self::MAX_SIZE_MB * 1024 * 1024;
    }

    public static function allowedMimeTypes(): array
    {
        return self::ALLOWED_MIME;
    }

    public static function validationRules(bool $required, string $attribute = 'image'): array
    {
        return [
            $attribute => [
                $required ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/jpg,image/png,image/webp',
                'max:' . self::MAX_SIZE_MB * 1024,
            ],
        ];
    }

    public function upload(UploadedFile $file, string $directory): string
    {
        $disk = Storage::disk('public');

        if (! $disk->directoryExists($directory)) {
            $disk->makeDirectory($directory);
        }

        $name = $this->uniqueFilename($file);

        return $file->storeAs($directory, $name, 'public');
    }

    public function delete(?string $relativePath): void
    {
        if ($relativePath === null || $relativePath === '') {
            return;
        }

        if (str_starts_with($relativePath, 'http://') || str_starts_with($relativePath, 'https://')) {
            return;
        }

        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    public function replace(?string $oldRelativePath, UploadedFile $file, string $directory): string
    {
        $this->delete($oldRelativePath);

        return $this->upload($file, $directory);
    }

    public function uploadMany(array $files, string $directory): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $paths[] = $this->upload($file, $directory);
            }
        }

        return $paths;
    }

    public function deleteMany(array $paths): void
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }

    public static function galleryValidationRules(string $attribute = 'gallery'): array
    {
        return [
            $attribute => ['nullable', 'array'],
            $attribute . '.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/jpg,image/png,image/webp',
                'max:' . self::MAX_SIZE_MB * 1024,
            ],
        ];
    }

    private function uniqueFilename(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');

        if (! in_array($ext, self::ALLOWED_EXT, true)) {
            $ext = 'jpg';
        }

        $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $base = preg_replace('/[^A-Za-z0-9_-]/', '_', $base) ?: 'image';

        return uniqid('', true) . '_' . $base . '.' . $ext;
    }
}