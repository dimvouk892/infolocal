<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable(); // array of image paths
            $table->text('short_description')->nullable();
            $table->longText('full_content')->nullable();
            $table->foreignId('place_category_id')->nullable()->constrained('place_categories')->nullOnDelete();
            $table->json('coordinates')->nullable(); // { lat, lng } or map embed
            $table->string('status')->default('draft'); // draft | published
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index('status');
            $table->index(['place_category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
