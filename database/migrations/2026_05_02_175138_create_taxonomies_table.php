<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('slug');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('taxonomables', function (Blueprint $table) {
            $table->foreignId('taxonomy_id')->constrained()->cascadeOnDelete();
            $table->morphs('taxonomable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxonomables');
        Schema::dropIfExists('taxonomies');
    }
};
