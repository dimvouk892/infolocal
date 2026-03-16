<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('title_el')->nullable()->after('title');
            $table->text('short_description_el')->nullable()->after('short_description');
            $table->longText('full_content_el')->nullable()->after('full_content');
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn(['title_el', 'short_description_el', 'full_content_el']);
        });
    }
};
