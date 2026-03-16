<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('business_category_id')->nullable()->constrained('business_categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('opening_hours')->nullable(); // e.g. { "mon": "09:00-17:00", ... }
            $table->json('map_location')->nullable(); // { lat, lng }
            $table->json('social_links')->nullable(); // { facebook, instagram, ... }
            $table->string('status')->default('pending'); // pending | approved | published
            $table->integer('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
            $table->boolean('featured')->default(false);
            $table->timestamps();
            $table->index('status');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
