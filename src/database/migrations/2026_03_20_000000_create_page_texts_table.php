<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_texts', function (Blueprint $table) {
            $table->id();
            $table->string('page');
            $table->string('key');
            $table->string('locale', 5);
            $table->text('value');
            $table->timestamps();

            $table->unique(['page', 'key', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_texts');
    }
};

