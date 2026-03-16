<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->boolean('show_title_on_card')->default(true)->after('featured');
            $table->boolean('show_category_on_card')->default(true)->after('show_title_on_card');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['show_title_on_card', 'show_category_on_card']);
        });
    }
};
