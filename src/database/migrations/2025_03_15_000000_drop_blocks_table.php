<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('blocks');
    }

    public function down(): void
    {
        // Restore would require recreate_blocks_table; leave empty or re-run original migration
    }
};
