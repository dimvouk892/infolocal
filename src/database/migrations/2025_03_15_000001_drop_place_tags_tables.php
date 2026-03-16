<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('place_tag');
        Schema::dropIfExists('place_tags');
    }

    public function down(): void
    {
        // Re-run original migrations to restore
    }
};
