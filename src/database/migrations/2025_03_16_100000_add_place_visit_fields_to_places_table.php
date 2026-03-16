<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('address')->nullable()->after('full_content');
            $table->string('video_url')->nullable()->after('address');
            $table->string('phone')->nullable()->after('video_url');
            $table->string('email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
            $table->boolean('featured')->default(false)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'video_url',
                'phone',
                'email',
                'website',
                'featured',
            ]);
        });
    }
};
