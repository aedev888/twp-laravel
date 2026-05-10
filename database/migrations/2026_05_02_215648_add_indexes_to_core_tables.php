<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->index('status');
            $table->index('published_at');
        });

        Schema::table('taxonomies', function (Blueprint $table) {
            $table->index('type');
        });

        Schema::table('navigations', function (Blueprint $table) {
            $table->index('location');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['published_at']);
        });

        Schema::table('taxonomies', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });

        Schema::table('navigations', function (Blueprint $table) {
            $table->dropIndex(['location']);
        });
    }
};

