<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The original create_posts_table migration used `->nullable` (property access)
     * instead of `->nullable()` (method call), so the `image` column was created
     * NOT NULL. That breaks creating any post without an image. Fix it here.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->text('image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->text('image')->nullable(false)->change();
        });
    }
};
