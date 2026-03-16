<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_categories', function (Blueprint $table) {
            $table->string('map_pin_icon')->default('map-pin')->after('sort_order');
            $table->string('map_pin_color', 7)->default('#10B981')->after('map_pin_icon');
        });

        Schema::table('place_categories', function (Blueprint $table) {
            $table->string('map_pin_icon')->default('map-pin')->after('sort_order');
            $table->string('map_pin_color', 7)->default('#10B981')->after('map_pin_icon');
        });
    }

    public function down(): void
    {
        Schema::table('business_categories', function (Blueprint $table) {
            $table->dropColumn(['map_pin_icon', 'map_pin_color']);
        });

        Schema::table('place_categories', function (Blueprint $table) {
            $table->dropColumn(['map_pin_icon', 'map_pin_color']);
        });
    }
};
