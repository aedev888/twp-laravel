<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->string('version')->nullable();
            $table->string('price_type')->default('free'); // free, premium
            $table->string('demo_url')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->json('seo')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('price_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
