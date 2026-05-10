<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Allow null email for Telegram-only users
            $table->string('email')->nullable()->change();
            // Allow null password for Telegram-only users (they never log in via password)
            $table->string('password')->nullable()->change();

            $table->bigInteger('telegram_id')->nullable()->unique()->after('email');
            $table->string('telegram_username')->nullable()->after('telegram_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['telegram_id', 'telegram_username']);
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
        });
    }
};
