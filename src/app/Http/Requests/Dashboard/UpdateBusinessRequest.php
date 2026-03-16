<?php

namespace App\Http\Requests\Dashboard;

use App\Services\ImageUploadService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        $business = $this->route('business');
        return $business && $this->user()?->businesses->contains($business);
    }

    public function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:50000'],
            'video_url' => ['nullable', 'string', 'url', 'max:500'],
            'address' => ['nullable', 'string', 'max:255'],
            'map_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'map_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:500'],
            'facebook' => ['nullable', 'string', 'url', 'max:255'],
            'instagram' => ['nullable', 'string', 'url', 'max:255'],
            'tripadvisor' => ['nullable', 'string', 'url', 'max:255'],
            'reviews_enabled' => ['boolean'],
            'reviews_require_approval' => ['boolean'],
            'village_id' => ['nullable', 'integer', 'exists:villages,id'],
            'gallery_keep' => ['nullable', 'array'],
            'gallery_keep.*' => ['string', 'max:500'],
        ], ImageUploadService::validationRules(false, 'featured_image'), ImageUploadService::validationRules(false, 'logo'), ImageUploadService::galleryValidationRules('gallery'));
    }

    public function messages(): array
    {
        return [
            'featured_image.mimes' => 'The image must be a JPG or JPEG file.',
            'featured_image.mimetypes' => 'The image must be a JPG or JPEG file.',
            'featured_image.max' => 'The image must not be larger than 2MB.',
        ];
    }
}
