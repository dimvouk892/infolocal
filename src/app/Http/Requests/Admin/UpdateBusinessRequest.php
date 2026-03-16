<?php

namespace App\Http\Requests\Admin;

use App\Services\ImageUploadService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return array_merge([
            'name' => ['required', 'string', 'max:255'],
            'name_el' => ['nullable', 'string', 'max:255'],
            'business_category_id' => ['nullable', 'exists:business_categories,id'],
            'description' => ['nullable', 'string', 'max:5000'],
            'description_el' => ['nullable', 'string', 'max:5000'],
            'video_url' => ['nullable', 'string', 'url', 'max:500'],
            'address' => ['nullable', 'string', 'max:255'],
            'map_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'map_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'facebook' => ['nullable', 'string', 'url', 'max:255'],
            'instagram' => ['nullable', 'string', 'url', 'max:255'],
            'tripadvisor' => ['nullable', 'string', 'url', 'max:255'],
            'status' => ['required', 'in:pending,approved,published'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'featured' => ['boolean'],
            'reviews_enabled' => ['boolean'],
            'reviews_require_approval' => ['boolean'],
            'gallery_keep' => ['nullable', 'array'],
            'gallery_keep.*' => ['string', 'max:500'],
            'village_id' => ['nullable', 'integer', 'exists:villages,id'],
        ], ImageUploadService::validationRules(false, 'featured_image'), ImageUploadService::validationRules(false, 'logo'), ImageUploadService::galleryValidationRules('gallery'));
    }

    public function messages(): array
    {
        return [
            'featured_image.mimes' => 'The featured image must be a JPG or JPEG file.',
            'featured_image.mimetypes' => 'The featured image must be a JPG or JPEG file.',
            'featured_image.max' => 'The featured image must not be larger than 2MB.',
        ];
    }
}
